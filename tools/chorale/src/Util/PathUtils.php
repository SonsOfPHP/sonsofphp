<?php

declare(strict_types=1);

namespace Chorale\Util;

final class PathUtils implements PathUtilsInterface
{
    public function normalize(string $path): string
    {
        $p = str_replace('\\', '/', $path);
        // remove multiple slashes
        $p = preg_replace('#/+#', '/', $p) ?? $p;
        // remove trailing slash (except root '/')
        if ($p !== '/' && str_ends_with($p, '/')) {
            $p = rtrim($p, '/');
        }
        // resolve "." and ".." cheaply (string-level, not FS)
        $parts = [];
        foreach (explode('/', $p) as $seg) {
            if ($seg === '' || $seg === '.') {
                continue;
            }
            if ($seg === '..') {
                array_pop($parts);
                continue;
            }
            $parts[] = $seg;
        }
        $out = implode('/', $parts);
        return $out === '' ? '.' : $out;
    }

    public function isUnder(string $path, string $root): bool
    {
        $p = $this->normalize($path);
        $r = $this->normalize($root);
        return $p === $r || str_starts_with($p, $r . '/');
    }

    public function match(string $pattern, string $path): bool
    {
        $pat = $this->normalize($pattern);
        $pth = $this->normalize($path);

        // Split into tokens while keeping the delimiters (** , * , ?)
        $parts = preg_split('/(\*\*|\*|\?)/', $pat, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if ($parts === false) {
            return false;
        }

        $regex = '';
        foreach ($parts as $part) {
            if ($part === '**') {
                $regex .= '.*';        // can cross slashes, zero or more
            } elseif ($part === '*') {
                $regex .= '[^/]*';     // single segment
            } elseif ($part === '?') {
                $regex .= '[^/]';      // one char in a segment
            } else {
                $regex .= preg_quote($part, '#'); // literal
            }
        }

        // full-string, case-sensitive; add 'i' if you want case-insensitive
        return (bool) preg_match('#^' . $regex . '$#u', $pth);
    }

    public function leaf(string $path): string
    {
        $p = $this->normalize($path);
        $pos = strrpos($p, '/');
        return $pos === false ? $p : substr($p, $pos + 1);
    }
}
