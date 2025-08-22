<?php

declare(strict_types=1);

namespace Chorale\Util;

/**
 * Path utilities for normalizing, matching, and extracting path segments.
 *
 * Examples:
 * - normalize('src//Foo/./Bar/..') => 'src/Foo'
 * - isUnder('src/Acme/Lib', 'src') => true
 * - match('src/*\/Lib', 'src/Acme/Lib') => true  (single-star within one segment)
 * - match('src/**\/Lib', 'src/a/b/c/Lib') => true  (double-star across directories)
 * - leaf('src/Acme/Lib') => 'Lib'
 */
final class PathUtils implements PathUtilsInterface
{
    public function normalize(string $path): string
    {
        $normalizedPath = str_replace('\\', '/', $path);
        // remove multiple slashes
        $normalizedPath = preg_replace('#/+#', '/', $normalizedPath) ?? $normalizedPath;
        // remove trailing slash (except root '/')
        if ($normalizedPath !== '/' && str_ends_with($normalizedPath, '/')) {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        // resolve "." and ".." cheaply (string-level, not FS)
        $resolvedSegments = [];
        foreach (explode('/', $normalizedPath) as $segment) {
            if ($segment === '') {
                continue;
            }

            if ($segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($resolvedSegments);
                continue;
            }

            $resolvedSegments[] = $segment;
        }

        $out = implode('/', $resolvedSegments);
        return $out === '' ? '.' : $out;
    }

    public function isUnder(string $path, string $root): bool
    {
        $normalizedPath = $this->normalize($path);
        $normalizedRoot = $this->normalize($root);
        return $normalizedPath === $normalizedRoot || str_starts_with($normalizedPath, $normalizedRoot . '/');
    }

    public function match(string $pattern, string $path): bool
    {
        $normalizedPattern = $this->normalize($pattern);
        $normalizedPath    = $this->normalize($path);

        // Split into tokens while keeping the delimiters (** , * , ?)
        $tokens = preg_split('/(\*\*|\*|\?)/', $normalizedPattern, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if ($tokens === false) {
            return false;
        }

        $regex = '';
        foreach ($tokens as $token) {
            if ($token === '**') {
                $regex .= '.*';        // can cross slashes, zero or more
            } elseif ($token === '*') {
                $regex .= '[^/]*';     // single segment
            } elseif ($token === '?') {
                $regex .= '[^/]';      // one char in a segment
            } else {
                $regex .= preg_quote($token, '#'); // literal
            }
        }

        // full-string, case-sensitive; add 'i' if you want case-insensitive
        return (bool) preg_match('#^' . $regex . '$#u', $normalizedPath);
    }

    public function leaf(string $path): string
    {
        $normalizedPath = $this->normalize($path);
        $pos = strrpos($normalizedPath, '/');
        return $pos === false ? $normalizedPath : substr($normalizedPath, $pos + 1);
    }
}
