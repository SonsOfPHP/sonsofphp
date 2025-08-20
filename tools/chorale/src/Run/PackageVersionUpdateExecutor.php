<?php

declare(strict_types=1);

namespace Chorale\Run;

use RuntimeException;

/**
 * Updates the version field in a package's composer.json.
 */
final class PackageVersionUpdateExecutor implements StepExecutorInterface
{
    public function supports(array $step): bool
    {
        return ($step['type'] ?? null) === 'package-version-update';
    }

    public function execute(string $projectRoot, array $step): void
    {
        $path = $step['path'] ?? null;
        $version = $step['version'] ?? null;
        if (!is_string($path) || !is_string($version)) {
            throw new RuntimeException('Invalid package-version-update step.');
        }

        $composerFile = rtrim($projectRoot, '/') . '/' . trim($path, '/') . '/composer.json';
        if (!is_file($composerFile)) {
            throw new RuntimeException('composer.json not found for path: ' . $path);
        }

        $data = json_decode((string) file_get_contents($composerFile), true);
        if (!is_array($data)) {
            throw new RuntimeException('Invalid composer.json for path: ' . $path);
        }

        $data['version'] = $version;
        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new RuntimeException('Failed to encode composer.json for path: ' . $path);
        }

        file_put_contents($composerFile, $encoded . "\n");
    }
}
