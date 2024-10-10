<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneInterface;
use SonsOfPHP\Component\Clock\ZoneOffset;

#[CoversClass(Zone::class)]
#[UsesClass(ZoneOffset::class)]
final class ZoneTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertInstanceOf(ZoneInterface::class, $zone);
    }

    public function testToString(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', (string) $zone);
        $this->assertSame('UTC', $zone->toString());
    }

    public function testGetName(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', $zone->getName());
    }

    public function testOffset(): void
    {
        $offset = new ZoneOffset(0);
        $zone   = new Zone('UTC', $offset);

        $this->assertSame($offset, $zone->getOffset());
    }
}
