<?php

declare(strict_types=1);

namespace Chorale\Tests\Discovery;

use Chorale\Discovery\PatternMatcher;
use Chorale\Util\PathUtilsInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PatternMatcher::class)]
#[Group('unit')]
#[Small]
final class PatternMatcherTest extends TestCase
{
    private function stubPaths(callable $fn): PathUtilsInterface
    {
        return new class ($fn) implements PathUtilsInterface {
            public function __construct(private $fn) {}
            public function normalize(string $path): string
            {
                return $path;
            }
            public function isUnder(string $path, string $root): bool
            {
                return false;
            }
            public function match(string $pattern, string $path): bool
            {
                $f = $this->fn;
                return (bool) $f($pattern, $path);
            }
            public function leaf(string $path): string
            {
                return $path;
            }
        };
    }

    #[Test]
    public function testFirstMatchReturnsIndex(): void
    {
        $pm = new PatternMatcher($this->stubPaths(fn($pat, $p) => $pat === 'src/*/Cookie'));
        $idx = $pm->firstMatch([
            ['match' => 'src/*/Cookie'],
        ], 'src/SonsOfPHP/Cookie');
        self::assertSame(0, $idx);
    }

    #[Test]
    public function testAllMatchesReturnsAllIndexes(): void
    {
        $pm = new PatternMatcher($this->stubPaths(fn($pat, $path) => in_array($pat, ['src/*/Cookie','src/SonsOfPHP/*'], true)));
        $idx = $pm->allMatches([
            ['match' => 'src/*/Cookie'],
            ['match' => 'src/SonsOfPHP/*'],
        ], 'src/SonsOfPHP/Cookie');
        self::assertSame([0,1], $idx);
    }
}
