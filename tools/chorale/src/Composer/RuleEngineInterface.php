<?php

declare(strict_types=1);

namespace Chorale\Composer;

interface RuleEngineInterface
{
    /**
     * Compute per-package composer.json edits based on rules/overrides.
     *
     * @param array<string,mixed> $packageComposer Current package composer.json
     * @param array<string,mixed> $rootComposer    Root composer.json
     * @param array<string,mixed> $config          chorale.yaml config
     * @param array<string,string> $context        { path, name }
     *
     * @return array<string,mixed> Changed keys only (what to write). Overridden values may include an internal '__override' flag.
     */
    public function computePackageEdits(array $packageComposer, array $rootComposer, array $config, array $context): array;
}
