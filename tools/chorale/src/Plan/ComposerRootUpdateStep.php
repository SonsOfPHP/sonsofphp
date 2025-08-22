<?php

declare(strict_types=1);

namespace Chorale\Plan;

/**
 * Update root composer.json to act as an aggregator (require/replace all packages),
 * and optionally set/confirm the root version.
 */
final readonly class ComposerRootUpdateStep implements PlanStepInterface
{
    /**
     * @param array<string,string> $require  package => version
     * @param array<string,string> $replace  package => version
     * @param array<string,mixed>  $meta
     */
    public function __construct(
        private string $rootPackageName,
        private ?string $rootVersion,
        private array $require,
        private array $replace = [],
        private array $meta = []
    ) {}

    public function type(): string
    {
        return 'composer-root-update';
    }

    public function id(): string
    {
        return $this->rootPackageName;
    }

    public function toArray(): array
    {
        return [
            'type'         => $this->type(),
            'root'         => $this->rootPackageName,
            'root_version' => $this->rootVersion,
            'require'      => $this->require,
            'replace'      => $this->replace,
            'meta'         => $this->meta,
        ];
    }
}
