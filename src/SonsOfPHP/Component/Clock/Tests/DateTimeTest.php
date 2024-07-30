<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Date;
use SonsOfPHP\Component\Clock\DateTime;
use SonsOfPHP\Component\Clock\DateTimeInterface;
use SonsOfPHP\Component\Clock\Time;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneOffset;

/**
 * @internal
 * @coversNothing
 */
#[CoversClass(DateTime::class)]
final class DateTimeTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $date = new Date(2022, 4, 20);
        $time = new Time(4, 20, 0, 0);
        $zone = new Zone('UTC', new ZoneOffset(0));

        $dateTime = new DateTime($date, $time, $zone);

        $this->assertInstanceOf(DateTimeInterface::class, $dateTime);
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\Date
     * @uses \SonsOfPHP\Component\Clock\Time
     * @uses \SonsOfPHP\Component\Clock\Zone
     * @uses \SonsOfPHP\Component\Clock\ZoneOffset
     */
    public function testToString(): void
    {
        $date = new Date(2022, 4, 20);
        $time = new Time(4, 20, 0, 0);
        $zone = new Zone('UTC', new ZoneOffset(0));

        $dateTime = new DateTime($date, $time, $zone);

        $this->assertSame('2022-04-20T04:20:00.000+00:00', (string) $dateTime);
        $this->assertSame('2022-04-20T04:20:00.000+00:00', $dateTime->toString());
    }
}
