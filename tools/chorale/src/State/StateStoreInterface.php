<?php

declare(strict_types=1);

namespace Chorale\State;

interface StateStoreInterface
{
    /** @return array<string,mixed> state payload (e.g., per-package fingerprints) */
    public function read(string $projectRoot): array;

    /** @param array<string,mixed> $state */
    public function write(string $projectRoot, array $state): void;
}
