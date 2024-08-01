<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Clock\Zone;
use SonsOfPHP\Component\Clock\ZoneInterface;
use SonsOfPHP\Component\Clock\ZoneOffset;

/**
 * @coversNothing
 */
#[CoversClass(Zone::class)]
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
     *
     * @uses \SonsOfPHP\Component\Clock\ZoneOffset
     */
    public function testToString(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', (string) $zone);
        $this->assertSame('UTC', $zone->toString());
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\ZoneOffset
     */
    public function testGetName(): void
    {
        $zone = new Zone('UTC', new ZoneOffset(0));

        $this->assertSame('UTC', $zone->getName());
    }

    /**
     *
     * @uses \SonsOfPHP\Component\Clock\ZoneOffset
     */
    public function testOffset(): void
    {
        $offset = new ZoneOffset(0);
        $zone   = new Zone('UTC', $offset);

        $this->assertSame($offset, $zone->getOffset());
    }
}
