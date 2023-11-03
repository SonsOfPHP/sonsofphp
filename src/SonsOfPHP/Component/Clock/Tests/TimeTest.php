<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Time;
use SonsOfPHP\Component\Clock\TimeInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Clock\Time
 */
final class TimeTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertInstanceOf(TimeInterface::class, $time);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toString
     * @covers ::getHour
     * @covers ::getMinute
     * @covers ::getSecond
     * @covers ::getMillisecond
     */
    public function testToString(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame('04:20:42.000', $time->toString());
        $this->assertSame('04:20:42.000', (string) $time);
    }

    /**
     * @covers ::__construct
     * @covers ::getHour
     */
    public function testGetHour(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertSame(4, $time->getHour());
    }

    /**
     * @covers ::__construct
     * @covers ::getMinute
     */
    public function testGetMinute(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertSame(20, $time->getMinute());
    }

    /**
     * @covers ::__construct
     * @covers ::getSecond
     */
    public function testGetSecond(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame(42, $time->getSecond());
    }

    /**
     * @covers ::__construct
     * @covers ::getMillisecond
     */
    public function testGetMillisecond(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame(0, $time->getMillisecond());
    }
}
