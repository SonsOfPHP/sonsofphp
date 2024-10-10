<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Year;
use SonsOfPHP\Component\Clock\YearInterface;

/**
 * @internal
 */
#[CoversClass(Year::class)]
#[CoversNothing]
final class YearTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $year = new Year(2022);

        $this->assertInstanceOf(YearInterface::class, $year);
    }

    public function testToString(): void
    {
        $year = new Year(2022);

        $this->assertSame('2022', $year->toString());
        $this->assertSame('2022', (string) $year);
    }

    public function testToInt(): void
    {
        $year = new Year(2022);

        $this->assertSame(2022, $year->toInt());
    }
}
