<?php

declare(strict_types=1);

namespace Chorale\Util;

interface DiffUtilInterface
{
    /**
     * Return true if the subset of keys differ between current and desired.
     *
     * @param array<string,mixed> $current
     * @param array<string,mixed> $desired
     * @param list<string> $keys
     */
    public function changed(array $current, array $desired, array $keys): bool;
}
