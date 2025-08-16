<?php

declare(strict_types=1);

namespace Chorale\Plan;

/**
 * Maintenance step (validate, normalize, etc.) after root edits.
 */
final readonly class ComposerRootRebuildStep implements PlanStepInterface
{
    /** @param list<string> $actions */
    public function __construct(
        private array $actions = ['validate']
    ) {}

    public function type(): string
    {
        return 'composer-root-rebuild';
    }

    public function id(): string
    {
        return 'composer-root-rebuild';
    }

    public function toArray(): array
    {
        return [
            'type'    => $this->type(),
            'actions' => $this->actions,
            'status'  => 'planned',
        ];
    }
}
