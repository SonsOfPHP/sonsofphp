<?php

declare(strict_types=1);

namespace Chorale\Plan;

final readonly class PackageVersionUpdateStep implements PlanStepInterface
{
    public function __construct(
        private string $path,
        private string $name,    // full composer name, e.g. "sonsofphp/cookie"
        private string $version, // e.g. "1.4.0"
        private string $reason = 'mismatch', // or 'missing'
        private ?string $currentVersion = null
    ) {}

    public function type(): string
    {
        return 'package-version-update';
    }

    public function id(): string
    {
        return $this->path;
    }

    public function toArray(): array
    {
        $out = [
            'type'    => $this->type(),
            'path'    => $this->path,
            'name'    => $this->name,
            'version' => $this->version,
            'reason'  => $this->reason,
        ];
        if ($this->currentVersion !== null) {
            $out['current_version'] = $this->currentVersion;
        }

        return $out;
    }
}
