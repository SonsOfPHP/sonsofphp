<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Contract\Logger\LevelInterface;

#[CoversClass(Level::class)]
#[UsesClass(Level::class)]
final class LevelTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $level = Level::Debug;

        $this->assertInstanceOf(LevelInterface::class, $level);
    }

    public function testFromNameWithInvalidName(): void
    {
        $this->expectException('ValueError');
        Level::fromName('testing invalid');
    }

    public function testFromName(): void
    {
        $this->assertSame(Level::Emergency, Level::fromName('emergency'));
        $this->assertSame(Level::Alert, Level::fromName('alert'));
        $this->assertSame(Level::Critical, Level::fromName('critical'));
        $this->assertSame(Level::Error, Level::fromName('error'));
        $this->assertSame(Level::Warning, Level::fromName('warning'));
        $this->assertSame(Level::Notice, Level::fromName('notice'));
        $this->assertSame(Level::Info, Level::fromName('info'));
        $this->assertSame(Level::Debug, Level::fromName('debug'));
    }

    public function testTryFromNameWithInvalidName(): void
    {
        $this->assertNotInstanceOf(LevelInterface::class, Level::tryFromName('invalid name'));
    }

    public function testTryFromName(): void
    {
        $this->assertSame(Level::Debug, Level::tryFromName('debug'));
    }

    public function testGetName(): void
    {
        $this->assertSame('DEBUG', Level::Debug->getName());
    }

    public function testEquals(): void
    {
        $this->assertTrue(Level::Debug->equals(Level::Debug));
        $this->assertFalse(Level::Debug->equals(Level::Info));
    }

    public function testIncludes(): void
    {
        $this->assertTrue(Level::Debug->includes(Level::Emergency));
        $this->assertFalse(Level::Info->includes(Level::Debug));
    }

    public function testIsHigherThan(): void
    {
        $this->assertTrue(Level::Emergency->isHigherThan(Level::Debug));
        $this->assertFalse(Level::Debug->isHigherThan(Level::Emergency));
    }

    public function testIsLowerThan(): void
    {
        $this->assertTrue(Level::Debug->isLowerThan(Level::Emergency));
        $this->assertFalse(Level::Emergency->isLowerThan(Level::Debug));
    }

    public function testToPstLogLevel(): void
    {
        $this->assertSame('debug', Level::Debug->toPsrLogLevel());
    }
}
