<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneInterface;
use SonsOfPHP\Component\Clock\ZoneOffset;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Clock\Zone
 */
final class ZoneTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertInstanceOf(ZoneInterface::class, $zone);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::toString
     */
    public function testToString(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', (string) $zone);
        $this->assertSame('UTC', $zone->toString());
    }

    /**
     * @covers ::__construct
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', $zone->getName());
    }

    /**
     * @covers ::__construct
     * @covers ::getOffset
     */
    public function testOffset(): void
    {
        $offset = new ZoneOffset(0);
        $zone = new Zone('UTC', $offset);

        $this->assertSame($offset, $zone->getOffset());
    }
}
