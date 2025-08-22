<?php

declare(strict_types=1);

namespace Chorale\Run;

use Chorale\Plan\PlanStepInterface;

interface RunnerInterface
{
    /** @return array{steps:list<PlanStepInterface>, noop?:array, exit_code?:int} */
    public function plan(string $projectRoot, array $options = []): array;

    /** @param list<array<string,mixed>> $steps */
    public function apply(string $projectRoot, array $steps): void;

    /** @return array{steps:list<PlanStepInterface>, noop?:array, exit_code?:int} */
    public function run(string $projectRoot, array $options = []): array;
}
