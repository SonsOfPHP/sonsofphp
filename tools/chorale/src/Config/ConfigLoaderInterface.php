<?php

declare(strict_types=1);

namespace Chorale\Config;

interface ConfigLoaderInterface
{
    /** Load chorale.yaml (if present) into an array; return [] when missing. */
    public function load(string $projectRoot): array;
}
