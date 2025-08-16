<?php

declare(strict_types=1);

namespace Chorale\Composer;

/**
 * Skeleton merger: returns empty requires with no conflicts.
 * Implement strategies: union-caret (default), union-loose, intersect, max.
 */
final readonly class DependencyMerger implements DependencyMergerInterface
{
    public function computeRootMerge(string $projectRoot, array $packagePaths, array $options = []): array
    {
        // TODO: Walk each package composer.json, gather require/require-dev,
        // filter monorepo packages, merge by strategy, compute conflicts.
        return [
            'require'     => [],
            'require-dev' => [],
            'conflicts'   => [],
        ];
    }
}
