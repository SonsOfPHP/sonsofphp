<?php

declare(strict_types=1);

namespace Chorale\Discovery;

use Chorale\Util\PathUtilsInterface;

/**
 * Matches package paths against a set of glob-like patterns.
 * Uses PathUtils::match to support '*', '?', and '**' semantics.
 *
 * Example:
 * - firstMatch([{match:'src/* /Lib'}], 'src/Acme/Lib') => 0
 * - allMatches([{match:'src/* /Lib'},{match:'src/Acme/*'}], 'src/Acme/Lib') => [0,1]
 */
final readonly class PatternMatcher implements PatternMatcherInterface
{
    public function __construct(
        private PathUtilsInterface $paths
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
