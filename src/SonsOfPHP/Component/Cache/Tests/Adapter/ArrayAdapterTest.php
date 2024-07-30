<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Cache\CacheItem;

#[CoversClass(ArrayAdapter::class)]
#[UsesClass(CacheItem::class)]
final class ArrayAdapterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    public function testGetItem(): void
    {
        $adapter = new ArrayAdapter();
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    public function testGetItemAfterSave(): void
    {
        $adapter = new ArrayAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->save($item);

        $this->assertTrue($adapter->getItem('unit.test')->isHit());
    }

    public function testGetItems(): void
    {
        $adapter = new ArrayAdapter();
        $items = $adapter->getItems(['unit.test']);
        foreach ($items as $key => $item) {
            $this->assertSame('unit.test', $key);
        }
    }

    public function testHasItem(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    public function testClear(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->clear());
    }

    public function testDeleteHasItem(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->deleteItem('item.key'));
    }

    public function testDeleteHasItems(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->deleteItems(['item.key']));
    }

    public function testCommit(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->commit());
    }

    public function testSave(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    public function testSaveDeferred(): void
    {
        $adapter = new ArrayAdapter();

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
