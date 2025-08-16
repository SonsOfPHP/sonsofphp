<?php

declare(strict_types=1);

namespace Chorale\Split;

/**
 * Uses lockfile state and (optionally) remote to decide if a split is needed.
 * This skeleton returns "forced" when force_split, otherwise empty reasons.
 */
final readonly class SplitDecider implements SplitDeciderInterface
{
    public function reasonsToSplit(string $projectRoot, string $packagePath, array $options = []): array
    {
        if (!empty($options['force_split'])) {
            return ['forced'];
        }

        // TODO: Compare current hash vs stored. Probe remote if verify_remote is true.
        // Return ["content-changed"] or ["repo-empty"] or ["missing-tag"] as appropriate.

        return [];
    }
}
