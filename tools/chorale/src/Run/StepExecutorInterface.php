<?php

declare(strict_types=1);

namespace Chorale\Run;

/**
 * Applies a single plan step.
 */
interface StepExecutorInterface
{
    /** @param array<string,mixed> $step */
    public function supports(array $step): bool;

    /** @param array<string,mixed> $step */
    public function execute(string $projectRoot, array $step): void;
}
