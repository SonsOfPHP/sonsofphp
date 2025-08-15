<?php

declare(strict_types=1);

namespace Chorale\Util;

final class Sorting implements SortingInterface
{
    public function sortPatterns(array $patterns): array
    {
        usort($patterns, static function (array $a, array $b): int {
            $am = (string) ($a['match'] ?? '');
            $bm = (string) ($b['match'] ?? '');
            $al = strlen($am);
            $bl = strlen($bm);
            if ($al === $bl) {
                return $am <=> $bm;
            }
            // longer match first (more specific wins)
            return $bl <=> $al;
        });

        return $patterns;
    }

    public function sortTargets(array $targets): array
    {
        usort($targets, static function (array $a, array $b): int {
            $ap = (string) ($a['path'] ?? '');
            $bp = (string) ($b['path'] ?? '');
            if ($ap === $bp) {
                $an = (string) ($a['name'] ?? '');
                $bn = (string) ($b['name'] ?? '');
                return $an <=> $bn;
            }
            return $ap <=> $bp;
        });

        return $targets;
    }
}
