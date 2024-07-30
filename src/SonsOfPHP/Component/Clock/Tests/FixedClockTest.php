<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use DateTimeZone;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use SonsOfPHP\Component\Clock\Exception\ClockException;
use SonsOfPHP\Component\Clock\FixedClock;

/**
 * @coversNothing
 */
#[CoversClass(FixedClock::class)]
final class FixedClockTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $clock = new FixedClock();

        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testTheDefaultTimezoneIsUTC(): void
    {
        $clock = new FixedClock();
        $this->assertSame('UTC', $clock->getZone()->getName());
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testSettingTheTimezoneInTheConstructorWorks(): void
    {
        $clock = new FixedClock(new DateTimeZone('America/New_York'));
        $this->assertSame('America/New_York', $clock->getZone()->getName());
    }

    /**
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testNowRemainsTheSame(): void
    {
        $clock   = new FixedClock();
        $tickOne = $clock->now();
        $tickTwo = $clock->now();
        $this->assertSame($tickOne, $tickTwo);
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testTickChangesTime(): void
    {
        $clock   = new FixedClock();
        $tickOne = $clock->now();
        usleep(100);
        $clock->tick();
        $tickTwo = $clock->now();
        $this->assertLessThan($tickTwo, $tickOne);
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testSetTimeWithTickTo(): void
    {
        $clock = new FixedClock();
        $clock->tickTo('2020-01-01 00:00:00');
        $tick = $clock->now();
        $this->assertSame('2020-01-01 00:00:00', $tick->format('Y-m-d H:i:s'));
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\Exception\ClockException
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testTickToThrowsExceptionWithInvalidInput(): void
    {
        $clock = new FixedClock();

        $this->expectException(ClockException::class);
        $clock->tickTo('20');
    }

    /**
     * @uses \SonsOfPHP\Component\Clock\FixedClock
     */
    public function testToStringMagicMethod(): void
    {
        $clock = new FixedClock();
        $this->assertSame('FixedClock[UTC]', (string) $clock);
    }
}
