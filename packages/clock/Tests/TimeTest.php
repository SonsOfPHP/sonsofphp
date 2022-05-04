<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use SonsOfPHP\Component\Clock\Time;
use SonsOfPHP\Component\Clock\TimeInterface;
use PHPUnit\Framework\TestCase;

final class TimeTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertInstanceOf(TimeInterface::class, $time);
    }

    public function testToString(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame('04:20:42.000', $time->toString());
        $this->assertSame('04:20:42.000', (string) $time);
    }

    public function testGetHour(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertSame(4, $time->getHour());
    }

    public function testGetMinute(): void
    {
        $time = new Time(4, 20, 0, 0);

        $this->assertSame(20, $time->getMinute());
    }

    public function testGetSecond(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame(42, $time->getSecond());
    }

    public function testGetMillisecond(): void
    {
        $time = new Time(4, 20, 42, 0);

        $this->assertSame(0, $time->getMillisecond());
    }
}
