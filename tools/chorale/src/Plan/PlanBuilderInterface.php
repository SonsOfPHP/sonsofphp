<?php

declare(strict_types=1);

namespace Chorale\Plan;

interface PlanBuilderInterface
{
    /**
     * Build a filtered, actionable plan.
     *
     * @param string $projectRoot  Absolute path to repo root
     * @param array<string,mixed> $config  Parsed chorale.yaml
     * @param array<string,mixed> $options keys: paths (list), show_all (bool), force_split (bool), verify_remote (bool), strict (bool)
     *
     * @return array{steps:list<PlanStepInterface>, noop:array<string,list<mixed>>, exit_code:int}
     */
    public function build(string $projectRoot, array $config, array $options = []): array;
}
