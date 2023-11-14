<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Logger\Level;
use SonsOfPHP\Contract\Logger\LevelInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Logger\Level
 *
 * @uses \SonsOfPHP\Component\Logger\Level
 */
final class LevelTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $level = Level::Debug;

        $this->assertInstanceOf(LevelInterface::class, $level);
    }

    /**
     * @covers ::fromName
     */
    public function testFromNameWithInvalidName(): void
    {
        $this->expectException('ValueError');
        Level::fromName('testing invalid');
    }

    /**
     * @covers ::fromName
     */
    public function testFromName(): void
    {
        $this->assertSame(Level::Debug, Level::fromName('debug'));
    }

    /**
     * @covers ::tryFromName
     */
    public function testTryFromNameWithInvalidName(): void
    {
        $this->assertNull(Level::tryFromName('invalid name'));
    }

    /**
     * @covers ::tryFromName
     */
    public function testTryFromName(): void
    {
        $this->assertSame(Level::Debug, Level::tryFromName('debug'));
    }

    /**
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $this->assertSame('DEBUG', Level::Debug->getName());
    }

    /**
     * @covers ::equals
     */
    public function testEquals(): void
    {
        $this->assertTrue(Level::Debug->equals(Level::Debug));
        $this->assertFalse(Level::Debug->equals(Level::Info));
    }

    /**
     * @covers ::includes
     */
    public function testIncludes(): void
    {
        $this->assertTrue(Level::Debug->includes(Level::Emergency));
        $this->assertFalse(Level::Info->includes(Level::Debug));
    }

    /**
     * @covers ::isHigherThan
     */
    public function testIsHigherThan(): void
    {
        $this->assertTrue(Level::Emergency->isHigherThan(Level::Debug));
        $this->assertFalse(Level::Debug->isHigherThan(Level::Emergency));
    }

    /**
     * @covers ::isLowerThan
     */
    public function testIsLowerThan(): void
    {
        $this->assertTrue(Level::Debug->isLowerThan(Level::Emergency));
        $this->assertFalse(Level::Emergency->isLowerThan(Level::Debug));
    }

    /**
     * @covers ::toPsrLogLevel
     */
    public function testToPstLogLevel(): void
    {
        $this->assertSame('debug', Level::Debug->toPsrLogLevel());
    }
}
