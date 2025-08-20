<?php

declare(strict_types=1);

namespace Chorale\Run;

use RuntimeException;

/**
 * Updates root composer.json so it can act as an installable package.
 */
final class ComposerRootUpdateExecutor implements StepExecutorInterface
{
    /** @param array<string,mixed> $step */
    public function supports(array $step): bool
    {
        return ($step['type'] ?? '') === 'composer-root-update';
    }

    /** @param array<string,mixed> $step */
    public function execute(string $projectRoot, array $step): void
    {
        $composerPath = rtrim($projectRoot, '/') . '/composer.json';
        $data = is_file($composerPath) ? json_decode((string) file_get_contents($composerPath), true) : [];
        if (!is_array($data)) {
            throw new RuntimeException('Invalid root composer.json');
        }

        $rootName = (string) ($step['root'] ?? $data['name'] ?? '');
        if ($rootName === '') {
            throw new RuntimeException('Root package name missing.');
        }
        $data['name'] = $rootName;

        $rootVersion = $step['root_version'] ?? null;
        if (is_string($rootVersion) && $rootVersion !== '') {
            $data['version'] = $rootVersion;
        }

        $data['require'] = (array) ($step['require'] ?? []);
        $data['replace'] = (array) ($step['replace'] ?? []);

        if (!empty($step['meta'])) {
            $data['extra']['chorale']['root-meta'] = $step['meta'];
        }

        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new RuntimeException('Failed to encode root composer.json');
        }

        file_put_contents($composerPath, $encoded . "\n");
    }
}
