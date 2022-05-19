<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Year;
use SonsOfPHP\Component\Clock\YearInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Clock\Year
 */
final class YearTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $year = new Year(2022);

        $this->assertInstanceOf(YearInterface::class, $year);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toString
     */
    public function testToString(): void
    {
        $year = new Year(2022);

        $this->assertSame('2022', $year->toString());
        $this->assertSame('2022', (string) $year);
    }

    /**
     * @covers ::__construct
     * @covers ::toInt
     */
    public function testToInt(): void
    {
        $year = new Year(2022);

        $this->assertSame(2022, $year->toInt());
    }
}
