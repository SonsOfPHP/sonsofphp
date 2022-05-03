<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Version\Tests;

use SonsOfPHP\Component\Version\Exception\VersionException;
use SonsOfPHP\Component\Version\Version;
use SonsOfPHP\Component\Version\VersionInterface;
use PHPUnit\Framework\TestCase;

final class VersionTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $version = new Version('1.2.3');

        $this->assertInstanceOf(VersionInterface::class, $version);
    }

    public function testItHasTheCorrectInterfaceWhenUsingFrom(): void
    {
        $version = Version::from('1.2.3');

        $this->assertInstanceOf(VersionInterface::class, $version);
    }

    /**
     * @dataProvider validVersions
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
     */
    public function testItThrowsExceptionForInvalidVersions(string $ver): void
    {
        $this->expectException(VersionException::class);
        $version = new Version($ver);
    }

    /**
     * @dataProvider compareVersions
     */
    public function testCompareWorksCorrectly(VersionInterface $v1, VersionInterface $v2, int $result): void
    {
        $this->assertSame($result, $v1->compare($v2));
    }

    public function validVersions(): iterable
    {
        yield ['0.0.4', 0, 0, 4];
        yield ['1.2.3', 1, 2, 3];
        yield ['10.20.30', 10, 20, 30];
        yield ['1.1.2-prerelease+meta', 1, 1, 2, 'prerelease', 'meta'];
        yield ['1.1.2+meta', 1, 1, 2, '', 'meta'];
    }

    public function invalidVersions(): iterable
    {
        yield ['1.1'];
        yield ['v1'];
        yield ['v1.2-4'];
    }

    public function compareVersions(): iterable
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
