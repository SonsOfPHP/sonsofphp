<?php

declare(strict_types=1);

namespace Chorale\Discovery;

interface PackageIdentityInterface
{
    /**
     * Build an identity string for a package (used to detect renames).
     * Prefer repo URL when available; otherwise derive from leaf name.
     *
     * @param string      $path    e.g. "src/SonsOfPHP/Contracts"
     * @param string|null $repoUrl optional, e.g. "git@github.com:SonsOfPHP/contracts.git"
     */
    public function identityFor(string $path, ?string $repoUrl = null): string;
}
