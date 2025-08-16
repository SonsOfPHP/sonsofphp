<?php

declare(strict_types=1);

namespace Chorale\Split;

/**
 * Minimal placeholder implementation.
 * Replace with a proper tree hash (e.g., SHA-256 over sorted file list).
 */
final class ContentHasher implements ContentHasherInterface
{
    public function hash(string $projectRoot, string $packagePath, array $ignoreGlobs = []): string
    {
        // Skeleton: do not traverse; just combine mtime + path as a stub.
        // Implement your deterministic file-walk hashing here.
        $key = $projectRoot . '|' . $packagePath . '|' . implode(',', $ignoreGlobs);
        return hash('sha256', $key);
    }
}
