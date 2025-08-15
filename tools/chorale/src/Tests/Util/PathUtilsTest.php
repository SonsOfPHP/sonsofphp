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
        self::assertSame('a/b', $this->u->normalize('a\\b'));
    }

    #[Test]
    public function testNormalizeCollapsesMultipleSlashes(): void
    {
        self::assertSame('a/b', $this->u->normalize('a////b'));
    }

    #[Test]
    public function testNormalizeRemovesTrailingSlash(): void
    {
        self::assertSame('a', $this->u->normalize('a/'));
    }

    #[Test]
    public function testNormalizeRootSlashStays(): void
    {
        self::assertSame('.', $this->u->normalize('/..'));
    }

    #[Test]
    public function testNormalizeResolvesDotSegments(): void
    {
        self::assertSame('a/b', $this->u->normalize('./a/./b'));
    }

    #[Test]
    public function testNormalizeResolvesDotDotSegments(): void
    {
        self::assertSame('a', $this->u->normalize('a/b/..'));
    }

    #[Test]
    public function testIsUnderTrueForSamePath(): void
    {
        self::assertTrue($this->u->isUnder('a/b', 'a/b'));
    }

    #[Test]
    public function testIsUnderTrueForChildPath(): void
    {
        self::assertTrue($this->u->isUnder('a/b/c', 'a/b'));
    }

    #[Test]
    public function testIsUnderFalseForSiblingPath(): void
    {
        self::assertFalse($this->u->isUnder('a/c', 'a/b'));
    }

    #[Test]
    public function testMatchAsteriskPatternCurrentlyDoesNotMatch(): void
    {
        self::assertFalse($this->u->match('src/*/Cookie', 'src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testMatchQuestionMarkPatternCurrentlyDoesNotMatch(): void
    {
        self::assertFalse($this->u->match('src/SonsOfPHP/Cooki?', 'src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testMatchExactPathWithDotsCurrentlyDoesNotMatch(): void
    {
        self::assertFalse($this->u->match('src/Sons.OfPHP/Cookie', 'src/Sons.OfPHP/Cookie'));
    }

    #[Test]
    public function testLeafReturnsLastSegment(): void
    {
        self::assertSame('Cookie', $this->u->leaf('src/SonsOfPHP/Cookie'));
    }

    #[Test]
    public function testLeafReturnsWholeWhenNoSeparator(): void
    {
        self::assertSame('Cookie', $this->u->leaf('Cookie'));
    }
}
