<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use SonsOfPHP\Component\Clock\ClockException;
use SonsOfPHP\Component\Clock\ClockInterface;
use SonsOfPHP\Component\Clock\TestClock;
use PHPUnit\Framework\TestCase;
use DateTimeZone;

final class TestClockTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $clock = new TestClock();

        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testTheDefaultTimezoneIsUTC(): void
    {
        $clock = new TestClock();
        $this->assertSame('UTC', $clock->timezone()->getName());
    }

    public function testSettingTheTimezoneInTheConstructorWorks(): void
    {
        $clock = new TestClock(new DateTimeZone('America/New_York'));
        $this->assertSame('America/New_York', $clock->timezone()->getName());
    }

    public function testNowRemainsTheSame(): void
    {
        $clock = new TestClock();
        $tickOne = $clock->now();
        $tickTwo = $clock->now();
        $this->assertSame($tickOne, $tickTwo);
    }

    public function testTickChangesTime(): void
    {
        $clock = new TestClock();
        $tickOne = $clock->now();
        usleep(100);
        $clock->tick();
        $tickTwo = $clock->now();
        $this->assertTrue($tickOne < $tickTwo);
    }

    public function testSetTimeWithTickTo(): void
    {
        $clock = new TestClock();
        $clock->tickTo('2020-01-01 00:00:00');
        $tick = $clock->now();
        $this->assertSame('2020-01-01 00:00:00', $tick->format('Y-m-d H:i:s'));
    }

    public function testTickToThrowsExceptionWithInvalidInput(): void
    {
        $clock = new TestClock();

        $this->expectException(ClockException::class);
        $clock->tickTo('20');
    }
}
