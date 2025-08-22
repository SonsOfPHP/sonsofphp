<?php

declare(strict_types=1);

namespace Chorale\Discovery;

final class PackageIdentity implements PackageIdentityInterface
{
    public function identityFor(string $path, ?string $repoUrl = null): string
    {
        if ($repoUrl !== null && $repoUrl !== '') {
            // Normalize protocol-ish noise; keep case-insensitive
            $s = trim($repoUrl);
            $s = preg_replace('#^(ssh://|git\+ssh://|https?://)#i', '', $s) ?? $s;
            return mb_strtolower($s);
        }

        // Fallback: use leaf directory name (case-insensitive)
        $p = str_replace('\\', '/', $path);
        $p = rtrim($p, '/');

        $leaf = $p === '' ? '' : substr($p, (int) (strrpos($p, '/') ?: -1) + 1);
        return mb_strtolower($leaf);
    }
}
