<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\ClockInterface;
use SonsOfPHP\Component\Clock\SystemClock;

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
        $this->assertSame('UTC', $clock->getZone()->getName());
    }

    public function testSetTimezoneWithObject(): void
    {
        $clock = new SystemClock(new DateTimeZone('America/New_York'));
        $this->assertSame('America/New_York', $clock->getZone()->getName());
    }

    public function testToStringMagicMethod(): void
    {
        $clock = new SystemClock();
        $this->assertSame('SystemClock[UTC]', (string) $clock);
    }
}
