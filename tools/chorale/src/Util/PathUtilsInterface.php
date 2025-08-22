<?php

declare(strict_types=1);

namespace Chorale\Util;

interface PathUtilsInterface
{
    /** Normalize separators, remove trailing slashes, resolve dots (no FS access). */
    public function normalize(string $path): string;

    /** Return true if $path is under $root (string compare only, case‑sensitive). */
    public function isUnder(string $path, string $root): bool;

    /**
     * Basic glob-to-regex matcher supporting '*' and '?' (no brace sets).
     * Example: match("src/SonsOfPHP/Component/*", "src/SonsOfPHP/Component/Cookie") === true
     */
    public function match(string $pattern, string $path): bool;

    /**
     * Return the last segment (basename) without trailing slash.
     * Example: leaf("src/SonsOfPHP/Component/Cookie") === "Cookie"
     */
    public function leaf(string $path): string;
}
