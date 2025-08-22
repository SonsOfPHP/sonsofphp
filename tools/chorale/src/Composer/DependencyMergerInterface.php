<?php

declare(strict_types=1);

namespace Chorale\Composer;

interface DependencyMergerInterface
{
    /**
     * Compute root require/require-dev from packages per strategy.
     *
     * @param list<string> $packagePaths
     * @param array<string,mixed> $options keys: strategy_require, strategy_require_dev, exclude_monorepo_packages(bool)
     * @return array{require:array<string,string>, require-dev:array<string,string>, conflicts:list<array<string,mixed>>}
     */
    public function computeRootMerge(string $projectRoot, array $packagePaths, array $options = []): array;
}
