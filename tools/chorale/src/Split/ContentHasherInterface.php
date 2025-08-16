<?php

declare(strict_types=1);

namespace Chorale\Split;

interface ContentHasherInterface
{
    /**
     * Produce a deterministic fingerprint of the package content at HEAD.
     * Implementations should respect ignore patterns (vendor/, lockfiles, etc.).
     *
     * @param string $projectRoot absolute repo root
     * @param string $packagePath relative path to the package (e.g., "src/SonsOfPHP/Cookie")
     * @param list<string> $ignoreGlobs glob patterns to ignore (relative to package root)
     */
    public function hash(string $projectRoot, string $packagePath, array $ignoreGlobs = []): string;
}
