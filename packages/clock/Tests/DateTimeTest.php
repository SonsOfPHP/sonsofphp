<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Date;
use SonsOfPHP\Component\Clock\DateTime;
use SonsOfPHP\Component\Clock\DateTimeInterface;
use SonsOfPHP\Component\Clock\Time;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneOffset;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Clock\DateTime
 */
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
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toString
     * @covers ::getDate
     * @covers ::getTime
     * @covers ::getZone
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
