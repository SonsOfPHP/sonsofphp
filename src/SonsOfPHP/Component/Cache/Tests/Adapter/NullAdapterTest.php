<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\Adapter\NullAdapter;
use SonsOfPHP\Component\Cache\CacheItem;

#[CoversClass(NullAdapter::class)]
#[UsesClass(CacheItem::class)]
final class NullAdapterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    public function testGetItem(): void
    {
        $adapter = new NullAdapter();
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    public function testGetItems(): void
    {
        $adapter = new NullAdapter();
        $items = $adapter->getItems(['unit.test']);
        foreach ($items as $key => $item) {
            $this->assertSame('unit.test', $key);
        }
    }

    public function testHasItem(): void
    {
        $adapter = new NullAdapter();

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    public function testClear(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->clear());
    }

    public function testDeleteHasItem(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->deleteItem('item.key'));
    }

    public function testDeleteHasItems(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->deleteItems(['item.key']));
    }

    public function testCommit(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->commit());
    }

    public function testSave(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    public function testSaveDeferred(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
