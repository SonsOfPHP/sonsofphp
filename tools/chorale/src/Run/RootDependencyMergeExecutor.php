<?php

declare(strict_types=1);

namespace Chorale\Run;

use RuntimeException;

/**
 * Applies merged package requirements to the root composer.json file.
 */
final class RootDependencyMergeExecutor implements StepExecutorInterface
{
    /** @param array<string,mixed> $step */
    public function supports(array $step): bool
    {
        return ($step['type'] ?? '') === 'composer-root-merge';
    }

    /** @param array<string,mixed> $step */
    public function execute(string $projectRoot, array $step): void
    {
        $composerPath = rtrim($projectRoot, '/') . '/composer.json';
        $data = is_file($composerPath) ? json_decode((string) file_get_contents($composerPath), true) : [];
        if (!is_array($data)) {
            throw new RuntimeException('Invalid root composer.json');
        }

        $require = (array) ($step['require'] ?? []);
        $requireDev = (array) ($step['require-dev'] ?? []);
        ksort($require);
        ksort($requireDev);
        $data['require'] = $require;
        $data['require-dev'] = $requireDev;

        if (!empty($step['conflicts'])) {
            $data['extra']['chorale']['dependency-conflicts'] = $step['conflicts'];
        } else {
            unset($data['extra']['chorale']['dependency-conflicts']);
        }

        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new RuntimeException('Failed to encode root composer.json');
        }

        file_put_contents($composerPath, $encoded . "\n");
    }
}
