<?php

declare(strict_types=1);

namespace Chorale\Discovery;

interface PatternMatcherInterface
{
    /**
     * Return the first matching pattern index for $path or null.
     * @param array<int, array<string,mixed>> $patterns
     */
    public function firstMatch(array $patterns, string $path): ?int;

    /**
     * Return all matching pattern indexes (ordered).
     * @param array<int, array<string,mixed>> $patterns
     * @return list<int>
     */
    public function allMatches(array $patterns, string $path): array;
}
