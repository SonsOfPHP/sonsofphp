<?php

declare(strict_types=1);

namespace Chorale\Config;

interface ConfigNormalizerInterface
{
    /**
     * Return a normalized, DRY config:
     *  - remove overrides equal to inherited defaults
     *  - sort patterns/targets deterministically
     *  - ensure minimal keys order for clean diffs
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    public function normalize(array $config): array;
}
