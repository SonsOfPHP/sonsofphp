<?php

declare(strict_types=1);

namespace Chorale\Diff;

interface ConfigDifferInterface
{
    /**
     * Compare discovered packages (+ computed repo) with existing config.
     *
     * @param array<string,mixed> $config     full config array
     * @param list<string>        $paths      discovered package paths (e.g., ["src/.../Cookie"])
     * @param array<string,mixed> $context    helpers: defaults, patternsByIndex, targetsByPath, checkers, etc.
     *
     * @return array<string, array<int, array<string,mixed>>> keyed by group:
     *   - new, renamed, drift, issues, conflicts, ok
     */
    public function diff(array $config, array $paths, array $context): array;
}
