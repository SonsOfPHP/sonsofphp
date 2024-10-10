<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use SonsOfPHP\Component\Clock\SystemClock;

/**
 * @internal
 */
#[CoversClass(SystemClock::class)]
#[CoversNothing]
final class SystemClockTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $clock = new SystemClock();

        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testImmutable(): void
    {
        $clock   = new SystemClock();
        $tickOne = $clock->now();
        usleep(1);
        $tickTwo = $clock->now();

        $this->assertInstanceOf(DateTimeImmutable::class, $tickOne);
        $this->assertInstanceOf(DateTimeImmutable::class, $tickTwo);
        $this->assertLessThan($tickTwo, $tickOne);
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
