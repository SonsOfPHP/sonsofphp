<?php

declare(strict_types=1);

namespace Chorale\Run;

use Chorale\Config\ConfigLoaderInterface;
use Chorale\Plan\PlanBuilderInterface;
use Chorale\Plan\PlanStepInterface;
use RuntimeException;

final class Runner implements RunnerInterface
{
    public function __construct(
        private readonly ConfigLoaderInterface $configLoader,
        private readonly PlanBuilderInterface $planner,
        private readonly StepExecutorRegistry $executors,
    ) {}

    public function plan(string $projectRoot, array $options = []): array
    {
        $config = $this->configLoader->load($projectRoot);
        if ($config === []) {
            throw new RuntimeException('No chorale.yaml found.');
        }

        return $this->planner->build($projectRoot, $config, $options);
    }

    public function apply(string $projectRoot, array $steps): void
    {
        foreach ($steps as $step) {
            $this->executors->execute($projectRoot, $step);
        }
    }

    public function run(string $projectRoot, array $options = []): array
    {
        $result = $this->plan($projectRoot, $options);
        $arrays = array_map(static fn(PlanStepInterface $s): array => $s->toArray(), $result['steps'] ?? []);
        $this->apply($projectRoot, $arrays);
        return $result;
    }
}
