<?php

declare(strict_types=1);

namespace Chorale\Config;

interface ConfigWriterInterface
{
    /**
     * Atomically write chorale.yaml at project root (with backup created beforehand).
     * @param array<string,mixed> $config
     */
    public function write(string $projectRoot, array $config): void;
}
