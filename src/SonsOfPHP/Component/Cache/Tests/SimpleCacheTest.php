<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\SimpleCache\CacheInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\SimpleCache;

#[CoversClass(SimpleCache::class)]
#[UsesClass(SimpleCache::class)]
final class SimpleCacheTest extends TestCase
{
    private MockObject $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $cache = new SimpleCache($this->adapter);

        $this->assertInstanceOf(CacheInterface::class, $cache);
    }

    public function testGetWhenHit(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(true);
        $item->method('get')->willReturn('item.value');

        $this->adapter->method('getItem')->willReturn($item);

        $cache = new SimpleCache($this->adapter);

        $this->assertSame('item.value', $cache->get('item.key'));
    }

    public function testGetWhenMiss(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->method('isHit')->willReturn(false);

        $this->adapter->method('getItem')->willReturn($item);

        $cache = new SimpleCache($this->adapter);

        $this->assertSame('default.value', $cache->get('item.key', 'default.value'));
    }

    public function testDelete(): void
    {
        $this->adapter->expects($this->once())->method('deleteItem')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->delete('item.key'));
    }

    public function testClear(): void
    {
        $this->adapter->expects($this->once())->method('clear')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->clear());
    }

    public function testHas(): void
    {
        $this->adapter->expects($this->once())->method('hasItem')->willReturn(false);

        $cache = new SimpleCache($this->adapter);

        $this->assertFalse($cache->has('item.key'));
    }

    public function testDeleteMultiple(): void
    {
        $this->adapter->expects($this->once())->method('deleteItems')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->deleteMultiple(['item.key']));
    }

    public function testSet(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->expects($this->once())->method('set');

        $this->adapter->expects($this->once())->method('getItem')->willReturn($item);
        $this->adapter->expects($this->once())->method('save')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->set('item.key', 'item.value'));
    }

    public function testSetMultiple(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->expects($this->once())->method('set');

        $this->adapter->expects($this->once())->method('getItem')->willReturn($item);
        $this->adapter->expects($this->once())->method('save')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->setMultiple(['item.key' => 'item.value']));
    }

    public function testGetMultiple(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->expects($this->never())->method('get');
        $item->expects($this->once())->method('isHit')->willReturn(false);

        $item2 = $this->createMock(CacheItemInterface::class);
        $item2->expects($this->once())->method('get')->willReturn('item2.value');
        $item2->expects($this->once())->method('isHit')->willReturn(true);

        $this->adapter->expects($this->once())->method('getItems')->willReturn([
            'item.key' => $item,
            'item2' => $item2,
        ]);

        $cache = new SimpleCache($this->adapter);

        $items = iterator_to_array($cache->getMultiple(['item.key', 'item2'], 'default.value'));

        $this->assertArrayHasKey('item.key', $items);
        $this->assertArrayHasKey('item2', $items);

        $this->assertSame('default.value', $items['item.key']);
        $this->assertSame('item2.value', $items['item2']);
    }

    public function testItWillSaveWhenTTLIsSet(): void
    {
        $item = $this->createMock(CacheItemInterface::class);
        $item->expects($this->once())->method('set');
        $item->expects($this->once())->method('expiresAfter');

        $this->adapter->expects($this->once())->method('getItem')->willReturn($item);
        $this->adapter->expects($this->once())->method('save')->willReturn(true);

        $cache = new SimpleCache($this->adapter);

        $this->assertTrue($cache->set('item.key', 'item.value', 60));
    }
}
