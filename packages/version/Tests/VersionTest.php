<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Version\Exception\VersionException;
use SonsOfPHP\Component\Version\Version;
use SonsOfPHP\Component\Version\VersionInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Version\Version
 *
 * @internal
 */
final class VersionTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $version = new Version('1.2.3');

        $this->assertInstanceOf(VersionInterface::class, $version);
    }

    /**
     * @covers ::__construct
     * @covers ::from
     */
    public function testItHasTheCorrectInterfaceWhenUsingFrom(): void
    {
        $version = Version::from('1.2.3');

        $this->assertInstanceOf(VersionInterface::class, $version);
    }

    /**
     * @dataProvider validVersions
     *
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::getBuild
     * @covers ::getMajor
     * @covers ::getMinor
     * @covers ::getPatch
     * @covers ::getPreRelease
     * @covers ::toString
     */
    public function testItParsesCorrectly(string $ver, int $major, int $minor, int $patch, ?string $preRelease = '', ?string $build = ''): void
    {
        $version = new Version($ver);
        $this->assertSame($major, $version->getMajor());
        $this->assertSame($minor, $version->getMinor());
        $this->assertSame($patch, $version->getPatch());
        $this->assertSame($preRelease, $version->getPreRelease());
        $this->assertSame($build, $version->getBuild());
        $this->assertSame($ver, $version->toString());
        $this->assertSame($ver, (string) $version);
    }

    /**
     * @dataProvider invalidVersions
     *
     * @covers ::__construct
     */
    public function testItThrowsExceptionForInvalidVersions(string $ver): void
    {
        $this->expectException(VersionException::class);
        $version = new Version($ver);
    }

    /**
     * @dataProvider compareVersions
     *
     * @covers ::compare
     */
    public function testCompareWorksCorrectly(VersionInterface $v1, VersionInterface $v2, int $result): void
    {
        $this->assertSame($result, $v1->compare($v2));
    }

    /**
     * @covers ::nextPatch
     */
    public function testNextPatch(): void
    {
        $version = new Version('1.2.3');
        $newVer  = $version->nextPatch();

        $this->assertNotSame($version, $newVer);
        $this->assertSame('1.2.3', $version->toString());
        $this->assertSame('1.2.4', $newVer->toString());
    }

    /**
     * @covers ::nextMinor
     */
    public function testNextMinor(): void
    {
        $version = new Version('1.2.3');
        $newVer  = $version->nextMinor();

        $this->assertNotSame($version, $newVer);
        $this->assertSame('1.2.3', $version->toString());
        $this->assertSame('1.3.0', $newVer->toString());
    }

    /**
     * @covers ::nextMajor
     */
    public function testNextMajor(): void
    {
        $version = new Version('1.2.3');
        $newVer  = $version->nextMajor();

        $this->assertNotSame($version, $newVer);
        $this->assertSame('1.2.3', $version->toString());
        $this->assertSame('2.0.0', $newVer->toString());
    }

    /**
     * @covers ::isGreaterThan
     */
    public function testIsGreaterThan(): void
    {
        $version = new Version('1.2.3');

        $this->assertTrue($version->isGreaterThan(new Version('1.2.0')));
    }

    /**
     * @covers ::isLessThan
     */
    public function testIsLessThan(): void
    {
        $version = new Version('1.2.3');

        $this->assertTrue($version->isLessThan(new Version('1.2.4')));
    }

    /**
     * @covers ::isEqualTo
     */
    public function testIsEqualTo(): void
    {
        $version = new Version('1.2.3');

        $this->assertTrue($version->isEqualTo(new Version('1.2.3')));
    }

    public static function validVersions(): iterable
    {
        yield ['0.0.4', 0, 0, 4];
        yield ['1.2.3', 1, 2, 3];
        yield ['10.20.30', 10, 20, 30];
        yield ['1.1.2-prerelease+meta', 1, 1, 2, 'prerelease', 'meta'];
        yield ['1.1.2+meta', 1, 1, 2, '', 'meta'];
    }

    public static function invalidVersions(): iterable
    {
        yield ['1.1'];
        yield ['v1'];
        yield ['v1.2-4'];
    }

    public static function compareVersions(): iterable
    {
        // Major
        yield [new Version('1.0.0'), new Version('0.0.0'), 1];
        yield [new Version('0.0.0'), new Version('1.0.0'), -1];
        yield [new Version('1.0.0'), new Version('1.0.0'), 0];

        // Minor
        yield [new Version('0.1.0'), new Version('0.0.0'), 1];
        yield [new Version('0.0.0'), new Version('0.1.0'), -1];
        yield [new Version('0.1.0'), new Version('0.1.0'), 0];

        // Patch
        yield [new Version('0.0.1'), new Version('0.0.0'), 1];
        yield [new Version('0.0.0'), new Version('0.0.1'), -1];
        yield [new Version('0.0.1'), new Version('0.0.1'), 0];
    }
}
