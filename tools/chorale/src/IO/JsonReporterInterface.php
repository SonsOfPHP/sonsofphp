<?php

declare(strict_types=1);

namespace Chorale\IO;

interface JsonReporterInterface
{
    /**
     * @param array<string, mixed> $defaults
     * @param array<string, mixed> $discoverySets  keyed by group: new, renamed, drift, issues, conflicts, ok
     * @param array<int, array<string, mixed>> $actions action preview for "Summary (to be written)"
     */
    public function build(array $defaults, array $discoverySets, array $actions): string;
}
