<?php

declare(strict_types=1);

namespace Chorale\Discovery;

use Chorale\Util\PathUtilsInterface;

final class PatternMatcher implements PatternMatcherInterface
{
    public function __construct(
        private readonly PathUtilsInterface $paths
    ) {}

    public function firstMatch(array $patterns, string $path): ?int
    {
        foreach ($patterns as $i => $p) {
            $m = (string) ($p['match'] ?? '');
            if ($m !== '' && $this->paths->match($m, $path)) {
                return (int) $i;
            }
        }
        return null;
    }

    public function allMatches(array $patterns, string $path): array
    {
        $hits = [];
        foreach ($patterns as $i => $p) {
            $pattern = (string) ($p['match'] ?? '');
            if ($pattern !== '' && $this->paths->match($pattern, $path)) {
                $hits[] = (int) $i;
            }
        }
        return $hits;
    }
}
