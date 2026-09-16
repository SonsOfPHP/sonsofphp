<?php

declare(strict_types=1);

namespace Chorale\Discovery;

use Chorale\Util\PathUtilsInterface;

/**
 * Matches package paths against a set of glob-like patterns.
 * Uses PathUtils::match to support '*', '?', and '**' semantics.
 *
 * Example:
 * - firstMatch([{match:'src/*\/Lib'}], 'src/Acme/Lib') => 0
 * - allMatches([{match:'src/*\/Lib'},{match:'src/Acme/*'}], 'src/Acme/Lib') => [0,1]
 */
final readonly class PatternMatcher implements PatternMatcherInterface
{
    public function __construct(
        private PathUtilsInterface $paths
    ) {}

    public function firstMatch(array $patterns, string $path): ?int
    {
        foreach ($patterns as $index => $patternEntry) {
            $patternString = (string) ($patternEntry['match'] ?? '');
            if ($patternString !== '' && $this->paths->match($patternString, $path)) {
                return (int) $index;
            }
        }

        return null;
    }

    public function allMatches(array $patterns, string $path): array
    {
        $matchIndexes = [];
        foreach ($patterns as $index => $patternEntry) {
            $patternString = (string) ($patternEntry['match'] ?? '');
            if ($patternString !== '' && $this->paths->match($patternString, $path)) {
                $matchIndexes[] = (int) $index;
            }
        }

        return $matchIndexes;
    }
}
