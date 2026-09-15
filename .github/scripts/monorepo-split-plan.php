#!/usr/bin/env php
<?php

declare(strict_types=1);

$options = getopt('', [
    'config::',
    'base::',
    'head::',
    'help',
]);

if (isset($options['help'])) {
    fwrite(STDOUT, <<<'HELP'
Usage:
  php .github/scripts/monorepo-split-plan.php [--config=.github/monorepo-split.json] [--base=<git-ref>] [--head=<git-ref>]

Validates the monorepo split map and prints the read-only repository split plan.
This is a dry-run planner only. It does not run splitsh-lite and never pushes.

HELP);
    exit(0);
}

$root = dirname(__DIR__, 2);
$configPath = (string) ($options['config'] ?? '.github/monorepo-split.json');
$configFile = str_starts_with($configPath, '/') ? $configPath : $root . '/' . $configPath;
$base = isset($options['base']) ? (string) $options['base'] : null;
$head = isset($options['head']) ? (string) $options['head'] : null;

$errors = [];
$warnings = [];

if (!is_file($configFile)) {
    fail(['Split config was not found: ' . $configFile]);
}

$config = json_decode((string) file_get_contents($configFile), true);
if (!is_array($config)) {
    fail(['Split config is not valid JSON: ' . json_last_error_msg()]);
}

$packages = $config['packages'] ?? null;
if (!is_array($packages)) {
    fail(['Split config must define a packages array.']);
}

$allowedStatuses = ['active', 'pending', 'archived', 'disabled'];
$seenNames = [];
$seenPaths = [];
$seenRepositories = [];
$statusCounts = array_fill_keys($allowedStatuses, 0);

foreach ($packages as $index => $package) {
    $number = $index + 1;
    if (!is_array($package)) {
        $errors[] = sprintf('Package #%d must be an object.', $number);
        continue;
    }

    foreach (['name', 'path', 'branch', 'status'] as $requiredKey) {
        if (!isset($package[$requiredKey]) || '' === $package[$requiredKey]) {
            $errors[] = sprintf('Package #%d is missing "%s".', $number, $requiredKey);
        }
    }

    $name = (string) ($package['name'] ?? 'package #' . $number);
    $path = (string) ($package['path'] ?? '');
    $repository = $package['repository'] ?? null;
    $status = (string) ($package['status'] ?? '');

    if (isset($seenNames[$name])) {
        $errors[] = sprintf('Duplicate package name: %s.', $name);
    }
    $seenNames[$name] = true;

    if (isset($seenPaths[$path])) {
        $errors[] = sprintf('Duplicate package path: %s.', $path);
    }
    $seenPaths[$path] = true;

    if (null !== $repository && '' !== $repository) {
        if (!is_string($repository)) {
            $errors[] = sprintf('%s repository must be a string or null.', $name);
        } elseif (isset($seenRepositories[$repository])) {
            $errors[] = sprintf('Duplicate repository: %s.', $repository);
        } else {
            $seenRepositories[$repository] = true;
        }
    }

    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = sprintf('%s has invalid status "%s".', $name, $status);
    } else {
        ++$statusCounts[$status];
    }

    if ('active' === $status && (null === $repository || '' === $repository)) {
        $errors[] = sprintf('%s is active but has no repository.', $name);
    }

    $composerFile = $root . '/' . $path . '/composer.json';
    if ('archived' !== $status && !is_file($composerFile)) {
        $errors[] = sprintf('%s is %s but %s is missing.', $name, $status, $path . '/composer.json');
        continue;
    }

    if (is_file($composerFile)) {
        $composer = json_decode((string) file_get_contents($composerFile), true);
        if (!is_array($composer)) {
            $errors[] = sprintf('%s has invalid package composer.json.', $name);
            continue;
        }

        if (($composer['name'] ?? null) !== $name) {
            $errors[] = sprintf('%s has composer name mismatch: %s.', $name, (string) ($composer['name'] ?? 'missing'));
        }
    }
}

$composerPackagePaths = discoverComposerPackagePaths($root);
$missingFromConfig = array_values(array_diff($composerPackagePaths, array_keys($seenPaths)));
$extraConfiguredPaths = array_values(array_diff(array_keys($seenPaths), $composerPackagePaths));

foreach ($missingFromConfig as $path) {
    $errors[] = sprintf('Package composer file is missing from split config: %s/composer.json.', $path);
}

foreach ($extraConfiguredPaths as $path) {
    $package = findPackageByPath($packages, $path);
    if (is_array($package) && 'archived' === ($package['status'] ?? null)) {
        continue;
    }

    $warnings[] = sprintf('Configured path does not currently contain a package composer file: %s.', $path);
}

$changedFiles = [];
if (null !== $base || null !== $head) {
    if (null === $base || null === $head) {
        $errors[] = 'Both --base and --head are required when checking changed paths.';
    } else {
        $changedFiles = changedFiles($root, $base, $head, $errors);
    }
}

if ([] !== $errors) {
    printSummary($configFile, $packages, $statusCounts, $warnings, $changedFiles, [], []);
    fail($errors);
}

[$plannedPackages, $skippedPackages] = planPackages($packages, $changedFiles);
printSummary($configFile, $packages, $statusCounts, $warnings, $changedFiles, $plannedPackages, $skippedPackages);

exit(0);

/**
 * @param list<string> $errors
 */
function fail(array $errors): never
{
    fwrite(STDERR, PHP_EOL . 'Split plan failed:' . PHP_EOL);
    foreach ($errors as $error) {
        fwrite(STDERR, '  - ' . $error . PHP_EOL);
    }

    exit(1);
}

/**
 * @return list<string>
 */
function discoverComposerPackagePaths(string $root): array
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root . '/src/SonsOfPHP', FilesystemIterator::SKIP_DOTS)
    );

    $paths = [];
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || 'composer.json' !== $file->getFilename()) {
            continue;
        }

        $path = str_replace($root . '/', '', $file->getPath());
        if (str_contains($path, '/vendor/')) {
            continue;
        }

        $paths[] = $path;
    }

    sort($paths);

    return $paths;
}

/**
 * @param list<array<string, mixed>> $packages
 *
 * @return array<string, mixed>|null
 */
function findPackageByPath(array $packages, string $path): ?array
{
    foreach ($packages as $package) {
        if (is_array($package) && ($package['path'] ?? null) === $path) {
            return $package;
        }
    }

    return null;
}

/**
 * @param list<string> $errors
 *
 * @return list<string>
 */
function changedFiles(string $root, string $base, string $head, array &$errors): array
{
    $command = sprintf(
        'git -C %s diff --name-only %s %s 2>&1',
        escapeshellarg($root),
        escapeshellarg($base),
        escapeshellarg($head),
    );

    exec($command, $output, $exitCode);
    if (0 !== $exitCode) {
        $errors[] = sprintf('Unable to read changed files for %s..%s: %s', $base, $head, implode(PHP_EOL, $output));

        return [];
    }

    return array_values(array_filter($output, static fn (string $line): bool => '' !== $line));
}

/**
 * @param list<array<string, mixed>> $packages
 * @param list<string>              $changedFiles
 *
 * @return array{0: list<array<string, mixed>>, 1: list<array{name: string, reason: string}>}
 */
function planPackages(array $packages, array $changedFiles): array
{
    $planned = [];
    $skipped = [];
    $hasChangeSet = [] !== $changedFiles;

    foreach ($packages as $package) {
        if (!is_array($package)) {
            continue;
        }

        $name = (string) $package['name'];
        $path = rtrim((string) $package['path'], '/');
        $status = (string) $package['status'];

        if ('active' !== $status) {
            $skipped[] = ['name' => $name, 'reason' => $status];
            continue;
        }

        if ($hasChangeSet && !packageChanged($path, $changedFiles)) {
            $skipped[] = ['name' => $name, 'reason' => 'unchanged'];
            continue;
        }

        $planned[] = $package;
    }

    return [$planned, $skipped];
}

/**
 * @param list<string> $changedFiles
 */
function packageChanged(string $path, array $changedFiles): bool
{
    foreach ($changedFiles as $changedFile) {
        if ($changedFile === $path || str_starts_with($changedFile, $path . '/')) {
            return true;
        }
    }

    return false;
}

/**
 * @param list<array<string, mixed>>                 $packages
 * @param array<string, int>                         $statusCounts
 * @param list<string>                               $warnings
 * @param list<string>                               $changedFiles
 * @param list<array<string, mixed>>                 $plannedPackages
 * @param list<array{name: string, reason: string}>  $skippedPackages
 */
function printSummary(string $configFile, array $packages, array $statusCounts, array $warnings, array $changedFiles, array $plannedPackages, array $skippedPackages): void
{
    fwrite(STDOUT, 'Monorepo split dry-run plan' . PHP_EOL);
    fwrite(STDOUT, 'Config: ' . $configFile . PHP_EOL);
    fwrite(STDOUT, sprintf('Packages: %d active=%d pending=%d archived=%d disabled=%d',
        count($packages),
        $statusCounts['active'],
        $statusCounts['pending'],
        $statusCounts['archived'],
        $statusCounts['disabled'],
    ) . PHP_EOL);

    if ([] !== $warnings) {
        fwrite(STDOUT, PHP_EOL . 'Warnings:' . PHP_EOL);
        foreach ($warnings as $warning) {
            fwrite(STDOUT, '  - ' . $warning . PHP_EOL);
        }
    }

    if ([] !== $changedFiles) {
        fwrite(STDOUT, PHP_EOL . sprintf('Changed files considered: %d', count($changedFiles)) . PHP_EOL);
    } else {
        fwrite(STDOUT, PHP_EOL . 'Changed files considered: none, planning every active package' . PHP_EOL);
    }

    fwrite(STDOUT, PHP_EOL . sprintf('Would split/publish: %d package(s)', count($plannedPackages)) . PHP_EOL);
    foreach ($plannedPackages as $package) {
        fwrite(STDOUT, sprintf(
            '  - %s | path=%s | repository=%s | branch=%s',
            (string) $package['name'],
            (string) $package['path'],
            (string) $package['repository'],
            (string) $package['branch'],
        ) . PHP_EOL);
    }

    $skipCounts = [];
    foreach ($skippedPackages as $skippedPackage) {
        $reason = $skippedPackage['reason'];
        $skipCounts[$reason] = ($skipCounts[$reason] ?? 0) + 1;
    }

    if ([] !== $skipCounts) {
        ksort($skipCounts);
        fwrite(STDOUT, PHP_EOL . 'Skipped:' . PHP_EOL);
        foreach ($skipCounts as $reason => $count) {
            fwrite(STDOUT, sprintf('  - %s: %d', $reason, $count) . PHP_EOL);
        }
    }

    fwrite(STDOUT, PHP_EOL . 'Dry run only. No splitsh-lite command was run and nothing was pushed.' . PHP_EOL);
}
