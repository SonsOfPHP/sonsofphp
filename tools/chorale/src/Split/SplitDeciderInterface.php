<?php

declare(strict_types=1);

namespace Chorale\Split;

interface SplitDeciderInterface
{
    /**
     * Return reasons that require a split (empty means no split needed).
     *
     * @param array<string,mixed> $options keys: force_split(bool), verify_remote(bool), repo(string), branch(string), tag_strategy(string)
     * @return list<string> reasons e.g. ["content-changed","repo-empty","missing-tag","forced"]
     */
    public function reasonsToSplit(string $projectRoot, string $packagePath, array $options = []): array;
}
