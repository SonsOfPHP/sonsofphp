<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Date;
use SonsOfPHP\Component\Clock\DateInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Clock\Date
 *
 * @internal
 */
final class DateTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertInstanceOf(DateInterface::class, $date);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toString
     */
    public function testToString(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame('2022-04-20', $date->toString());
        $this->assertSame('2022-04-20', (string) $date);
    }

    /**
     * @covers ::__construct
     * @covers ::getYear
     */
    public function testGetYear(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(2022, $date->getYear());
    }

    /**
     * @covers ::__construct
     * @covers ::getMonth
     */
    public function testGetMonth(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(4, $date->getMonth());
    }

    /**
     * @covers ::__construct
     * @covers ::getDay
     */
    public function testGetDay(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(20, $date->getDay());
    }
}
