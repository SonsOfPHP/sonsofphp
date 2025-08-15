<?php

declare(strict_types=1);

namespace Chorale\Discovery;

interface PackageScannerInterface
{
    /**
     * Find candidate package paths under src/ (optionally narrowed by $paths).
     *
     * @param string $baseDir e.g. "src" or "packages" (relative to project root)
     * @param list<string> $paths relative to project root; if empty, scan "src/"
     * @return list<string> normalized relative paths like "src/SonsOfPHP/Cookie"
     */
    public function scan(string $projectRoot, string $baseDir, array $paths = []): array;
}
