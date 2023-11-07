<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\CacheItemFactory;
use SonsOfPHP\Component\Cache\CacheItemFactoryInterface;
use Psr\Cache\CacheItemInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\CacheItemFactory
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 */
final class CacheItemFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $factory = new CacheItemFactory();

        $this->assertInstanceOf(CacheItemFactoryInterface::class, $factory);
    }

    /**
     * @covers ::createCacheItem
     */
    public function testCreateCacheItem(): void
    {
        $factory = new CacheItemFactory();
        $item = $factory->createCacheItem(key: 'item.key', value: 'item.value');
        $this->assertSame('item.key', $item->getKey());
        $this->assertSame('item.value', $item->get());
        $this->assertFalse($item->isHit());
    }
}
