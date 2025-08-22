<?php

declare(strict_types=1);

namespace Chorale\Util;

/**
 * Deterministic sort helpers for patterns and targets.
 *
 * Examples:
 * - sortPatterns([{match: 'a/b'}, {match: 'a/b/c'}]) => 'a/b/c' first (more specific)
 * - sortTargets([{path:'b',name:'x'},{path:'a',name:'z'}]) => path 'a' first; ties break by name
 */
final class Sorting implements SortingInterface
{
    public function sortPatterns(array $patterns): array
    {
        usort($patterns, static function (array $a, array $b): int {
            $matchA = (string) ($a['match'] ?? '');
            $matchB = (string) ($b['match'] ?? '');
            $lenA = strlen($matchA);
            $lenB = strlen($matchB);
            if ($lenA === $lenB) {
                return $matchA <=> $matchB;
            }

            // longer match first (more specific wins)
            return $lenB <=> $lenA;
        });

        return $patterns;
    }

    public function sortTargets(array $targets): array
    {
        usort($targets, static function (array $a, array $b): int {
            $pathA = (string) ($a['path'] ?? '');
            $pathB = (string) ($b['path'] ?? '');
            if ($pathA === $pathB) {
                $nameA = (string) ($a['name'] ?? '');
                $nameB = (string) ($b['name'] ?? '');
                return $nameA <=> $nameB;
            }

            return $pathA <=> $pathB;
        });

        return $targets;
    }
}
