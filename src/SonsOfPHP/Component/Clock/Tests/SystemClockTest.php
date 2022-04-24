<?php declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use SonsOfPHP\Component\Clock\ClockException;
use SonsOfPHP\Component\Clock\ClockInterface;
use SonsOfPHP\Component\Clock\SystemClock;
use PHPUnit\Framework\TestCase;
use DateTimeZone;
use DateTimeImmutable;

final class SystemClockTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $clock = new SystemClock();

        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testImmutable(): void
    {
        $clock = new SystemClock();
        $tickOne = $clock->now();
        usleep(1);
        $tickTwo = $clock->now();

        $this->assertInstanceOf(DateTimeImmutable::class, $tickOne);
        $this->assertInstanceOf(DateTimeImmutable::class, $tickTwo);
        $this->assertTrue($tickOne < $tickTwo);
    }

    public function testDefaultTimezone(): void
    {
        $clock = new SystemClock();
        $this->assertSame('UTC', $clock->timezone()->getName());
    }

    public function testSetTimezoneWithObject(): void
    {
        $clock = new SystemClock(new DateTimeZone('America/New_York'));
        $this->assertSame('America/New_York', $clock->timezone()->getName());
    }
}
