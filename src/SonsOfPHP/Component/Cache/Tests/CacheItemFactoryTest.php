<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\CacheItemFactory;
use SonsOfPHP\Component\Cache\CacheItemFactoryInterface;

/**
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @coversNothing
 */
#[CoversClass(CacheItemFactory::class)]
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

    public function testCreateCacheItem(): void
    {
        $factory = new CacheItemFactory();
        $item = $factory->createCacheItem(key: 'item.key', value: 'item.value');
        $this->assertSame('item.key', $item->getKey());
        $this->assertSame('item.value', $item->get());
        $this->assertFalse($item->isHit());
    }
}
