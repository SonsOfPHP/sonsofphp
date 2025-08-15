<?php

declare(strict_types=1);

namespace Chorale\Repo;

interface RepoResolverInterface
{
    /**
     * Compute final repo URL for a package path using precedence:
     * target.repo > pattern.repo > default_repo_template (+ per-scope overrides).
     *
     * @param array<string,mixed> $defaults   resolved via ConfigDefaultsInterface
     * @param array<string,mixed> $pattern    pattern entry (if any)
     * @param array<string,mixed> $target     target entry (if any)
     * @param string              $path       package path, e.g. "src/SonsOfPHP/Cookie"
     * @param string|null         $name       derived name (leaf) or null to compute from $path
     */
    public function resolve(array $defaults, array $pattern, array $target, string $path, ?string $name = null): string;
}
