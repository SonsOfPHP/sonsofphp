<?php

declare(strict_types=1);

namespace Chorale\Plan;

/**
 * Merge package requires into root require/require-dev according to strategy.
 */
final readonly class RootDependencyMergeStep implements PlanStepInterface
{
    /**
     * @param array<string,string>        $require
     * @param array<string,string>        $requireDev
     * @param list<array<string,mixed>>   $conflicts
     */
    public function __construct(
        private array $require,
        private array $requireDev,
        private array $conflicts = []
    ) {}

    public function type(): string
    {
        return 'composer-root-merge';
    }

    public function id(): string
    {
        return 'composer-root-merge';
    }

    public function toArray(): array
    {
        return [
            'type'        => $this->type(),
            'require'     => $this->require,
            'require-dev' => $this->requireDev,
            'conflicts'   => $this->conflicts,
        ];
    }
}
