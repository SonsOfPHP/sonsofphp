<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneInterface;
use SonsOfPHP\Component\Clock\ZoneOffset;

#[CoversClass(Zone::class)]
#[CoversNothing]
final class ZoneTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertInstanceOf(ZoneInterface::class, $zone);
    }


    #[UsesClass(ZoneOffset::class)]
    public function testToString(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', (string) $zone);
        $this->assertSame('UTC', $zone->toString());
    }


    #[UsesClass(ZoneOffset::class)]
    public function testGetName(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', $zone->getName());
    }


    #[UsesClass(ZoneOffset::class)]
    public function testOffset(): void
    {
        $offset = new ZoneOffset(0);
        $zone   = new Zone('UTC', $offset);

        $this->assertSame($offset, $zone->getOffset());
    }
}
