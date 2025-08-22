<?php

declare(strict_types=1);

namespace Chorale\Tests\Util;

use Chorale\Util\PathUtils;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(PathUtils::class)]
#[Group('unit')]
#[Small]
final class PathUtilsTest extends TestCase
{
    private PathUtils $u;

    protected function setUp(): void
    {
        $this->u = new PathUtils();
    }

    #[Test]
    public function testNormalizeConvertsBackslashes(): void
    {
        $this->assertSame('a/b', $this->u->normalize('a\\b'));
    }

    #[Test]
    public function testNormalizeCollapsesMultipleSlashes(): void
    {
        $this->assertSame('a/b', $this->u->normalize('a////b'));
    }

    #[Test]
    public function testNormalizeRemovesTrailingSlash(): void
    {
        $this->assertSame('a', $this->u->normalize('a/'));
    }

    #[Test]
    public function testNormalizeRootSlashStays(): void
    {
        $this->assertSame('.', $this->u->normalize('/..'));
    }

    #[Test]
    public function testNormalizeResolvesDotSegments(): void
    {
        $this->assertSame('a/b', $this->u->normalize('./a/./b'));
    }

    #[Test]
    public function testNormalizeResolvesDotDotSegments(): void
    {
        $this->assertSame('a', $this->u->normalize('a/b/..'));
    }

    #[Test]
    public function testIsUnderTrueForSamePath(): void
    {
        $this->assertTrue($this->u->isUnder('a/b', 'a/b'));
    }

    #[Test]
    public function testIsUnderTrueForChildPath(): void
    {
        $this->assertTrue($this->u->isUnder('a/b/c', 'a/b'));
    }

    #[Test]
    public function testIsUnderFalseForSiblingPath(): void
    {
        $this->assertFalse($this->u->isUnder('a/c', 'a/b'));
    }

    #[Test]
    public function testMatchAsteriskPatternMatches(): void
    {
        $this->assertTrue($this->u->match('src/*/Cookie', 'src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testMatchQuestionMarkPatternMatches(): void
    {
        $this->assertTrue($this->u->match('src/SonsOfPHP/Cooki?', 'src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testMatchExactPathWithDotsCurrentlyDoesNotMatch(): void
    {
        $this->assertFalse($this->u->match('src/Sons.OfPHP/Cookie', 'src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testLeafReturnsLastSegment(): void
    {
        $this->assertSame('Cookie', $this->u->leaf('src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testLeafReturnsWholeWhenNoSeparator(): void
    {
        $this->assertSame('Cookie', $this->u->leaf('Cookie'));
    }

    #[Test]
    public function testMatchDoubleStarCrossesDirectories(): void
    {
        $this->assertTrue($this->u->match('src/**/Cookie', 'src/A/B/Cookie'));
    }
}
