<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Date;
use SonsOfPHP\Component\Clock\DateInterface;

#[CoversClass(Date::class)]
final class DateTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertInstanceOf(DateInterface::class, $date);
    }

    public function testToString(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame('2022-04-20', $date->toString());
        $this->assertSame('2022-04-20', (string) $date);
    }

    public function testGetYear(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(2022, $date->getYear());
    }

    public function testGetMonth(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(4, $date->getMonth());
    }

    public function testGetDay(): void
    {
        $date = new Date(2022, 4, 20);

        $this->assertSame(20, $date->getDay());
    }
}
