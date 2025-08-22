<?php

declare(strict_types=1);

namespace Chorale\Plan;

/**
 * Mirrors/merges selected composer sections to a package per the rule engine.
 */
final readonly class PackageMetadataSyncStep implements PlanStepInterface
{
    /**
     * @param array<string,mixed> $apply           Changed keys only (what will be written)
     * @param list<string>        $overridesUsed   Which keys came from overrides
     */
    public function __construct(
        private string $path,
        private string $name,
        private array $apply,
        private array $overridesUsed = []
    ) {}

    public function type(): string
    {
        return 'package-metadata-sync';
    }

    public function id(): string
    {
        return $this->path;
    }

    public function toArray(): array
    {
        return [
            'type'           => $this->type(),
            'path'           => $this->path,
            'name'           => $this->name,
            'apply'          => $this->apply,
            'overrides_used' => $this->overridesUsed,
        ];
    }
}
