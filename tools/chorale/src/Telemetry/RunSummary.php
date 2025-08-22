<?php

declare(strict_types=1);

namespace Chorale\Telemetry;

final class RunSummary implements RunSummaryInterface
{
    /** @var array<string,int> */
    private array $buckets = [];

    public function inc(string $bucket): void
    {
        if ($bucket === '') {
            return;
        }
        $this->buckets[$bucket] = ($this->buckets[$bucket] ?? 0) + 1;
    }

    public function all(): array
    {
        ksort($this->buckets);
        return $this->buckets;
    }
}
