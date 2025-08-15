<?php

declare(strict_types=1);

namespace Chorale\Telemetry;

interface RunSummaryInterface
{
    public function inc(string $bucket): void; // e.g. "new","renamed","drift","issues","conflicts","ok"

    /** @return array<string,int> */
    public function all(): array;
}
