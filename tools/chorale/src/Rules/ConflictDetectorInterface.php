<?php

declare(strict_types=1);

namespace Chorale\Rules;

interface ConflictDetectorInterface
{
    /**
     * Return true if multiple patterns match a path; also return the indexes matched.
     * @param array<int, array<string,mixed>> $patterns
     * @return array{conflict:bool, matches:list<int>}
     */
    public function detect(array $patterns, string $path): array;
}
