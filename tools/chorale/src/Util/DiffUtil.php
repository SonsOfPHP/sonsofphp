<?php

declare(strict_types=1);

namespace Chorale\Util;

/**
 * Minimal stable diff helper (strict equality per key).
 * Extend as needed for order-insensitive comparisons.
 */
final class DiffUtil implements DiffUtilInterface
{
    public function changed(array $current, array $desired, array $keys): bool
    {
        foreach ($keys as $k) {
            $a = $current[$k] ?? null;
            $b = $desired[$k] ?? null;
            if ($a !== $b) {
                return true;
            }
        }

        return false;
    }
}
