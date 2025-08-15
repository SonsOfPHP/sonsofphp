<?php

declare(strict_types=1);

namespace Chorale\Rules;

use Chorale\Discovery\PatternMatcherInterface;

final class ConflictDetector implements ConflictDetectorInterface
{
    public function __construct(
        private readonly PatternMatcherInterface $matcher
    ) {}

    public function detect(array $patterns, string $path): array
    {
        $matches = $this->matcher->allMatches($patterns, $path);
        return ['conflict' => count($matches) > 1, 'matches' => $matches];
    }
}
