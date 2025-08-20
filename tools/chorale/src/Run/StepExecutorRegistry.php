<?php

declare(strict_types=1);

namespace Chorale\Run;

use RuntimeException;

/**
 * Maps plan step types to executors.
 */
final class StepExecutorRegistry
{
    /** @var list<StepExecutorInterface> */
    private array $executors = [];

    /** @param iterable<StepExecutorInterface> $executors */
    public function __construct(iterable $executors = [])
    {
        foreach ($executors as $executor) {
            $this->executors[] = $executor;
        }
    }

    public function add(StepExecutorInterface $executor): void
    {
        $this->executors[] = $executor;
    }

    /** @param array<string,mixed> $step */
    public function execute(string $projectRoot, array $step): void
    {
        foreach ($this->executors as $executor) {
            if ($executor->supports($step)) {
                $executor->execute($projectRoot, $step);
                return;
            }
        }

        throw new RuntimeException('No executor registered for step type: ' . ($step['type'] ?? 'unknown'));
    }
}
