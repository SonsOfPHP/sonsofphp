<?php

declare(strict_types=1);

namespace Chorale\Util;

interface SortingInterface
{
    /**
     * Sort pattern definitions by specificity (longer match first), then stable index.
     * @param array<int, array<string, mixed>> $patterns
     * @return array<int, array<string, mixed>>
     */
    public function sortPatterns(array $patterns): array;

    /**
     * Sort targets by path asc (normalized), then by name.
     * @param array<int, array<string, mixed>> $targets
     * @return array<int, array<string, mixed>>
     */
    public function sortTargets(array $targets): array;
}
