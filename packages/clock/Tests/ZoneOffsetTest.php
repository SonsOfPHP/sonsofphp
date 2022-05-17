<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\ZoneOffset;
use SonsOfPHP\Component\Clock\ZoneOffsetInterface;

final class ZoneOffsetTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $zoneOffset = new ZoneOffset(-18000);

        $this->assertInstanceOf(ZoneOffsetInterface::class, $zoneOffset);
    }

    public function testToString(): void
    {
        $zoneOffset = new ZoneOffset(-19800);

        $this->assertSame('-05:30', $zoneOffset->toString());
        $this->assertSame('-05:30', (string) $zoneOffset);
    }

    public function testGetSeconds(): void
    {
        $zoneOffset = new ZoneOffset(-18000);

        $this->assertSame(-18000, $zoneOffset->getSeconds());
    }

    public function testGetHours(): void
    {
        $zoneOffset = new ZoneOffset(-18000);

        $this->assertSame(-5, $zoneOffset->getHours());
    }
}
