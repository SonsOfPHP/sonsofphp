<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\CacheItem;
use Psr\Cache\CacheItemInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\CacheItem
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 */
final class CacheItemTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $item = new CacheItem('testing');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    /**
     * @covers ::__construct
     * @covers ::getKey
     */
    public function testGetKeyWorksAsExpected(): void
    {
        $item = new CacheItem('testing');

        $this->assertSame('testing', $item->getKey());
    }

    /**
     * @covers ::isHit
     */
    public function testDefaultValueForIsHit(): void
    {
        $item = new CacheItem('testing');

        $this->assertFalse($item->isHit());
    }

    /**
     * @covers ::get
     */
    public function testDefaultValueForGet(): void
    {
        $item = new CacheItem('testing');

        $this->assertNull($item->get());
    }

    /**
     * @covers ::set
     */
    public function testSetWorksAsExpected(): void
    {
        $item = new CacheItem('testing');
        $this->assertSame($item, $item->set('value'));
        $this->assertSame('value', $item->get());
    }

    /**
     * @covers ::expiresAfter
     */
    public function testExpiresAfterWorksAsExpectedWithIntegers(): void
    {
        $item = new CacheItem('testing');
        $itemAsArray = (array) $item;
        $this->assertFalse(isset($itemAsArray["\0*\0expiry"]));

        $item->expiresAfter(3600);
        $itemAsArray = (array) $item;
        $this->assertNotNull($itemAsArray["\0*\0expiry"]);
    }
}
