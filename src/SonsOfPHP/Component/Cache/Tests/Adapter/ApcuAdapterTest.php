<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Component\Cache\CacheItem;

#[RequiresPhpExtension('apcu')]
#[CoversClass(ApcuAdapter::class)]
#[UsesClass(CacheItem::class)]
final class ApcuAdapterTest extends TestCase
{
    public function setUp(): void
    {
        apcu_clear_cache();
    }

    protected function tearDown(): void
    {
        apcu_clear_cache();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    public function testGetItem(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    public function testGetItemAfterSave(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->save($item);

        $this->assertTrue($adapter->getItem('unit.test')->isHit());
    }

    public function testGetItems(): void
    {
        $adapter = new ApcuAdapter();
        $items = $adapter->getItems(['unit.test']);
        foreach ($items as $key => $item) {
            $this->assertSame('unit.test', $key);
        }
    }

    public function testHasItem(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    public function testClear(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->clear());
    }

    public function testDeleteItem(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->deleteItem('item.key'));
    }

    public function testDeleteItemsWithValuesInCache(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->save($item);

        $this->assertTrue($adapter->deleteItems(['unit.test']));
    }

    public function testDeleteItemsWithEmptyCache(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->deleteItems(['item.key']));
    }

    public function testCommit(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->commit());
    }

    public function testCommitWithDeferredValues(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->saveDeferred($item);

        $this->assertFalse($adapter->hasItem('unit.test'));
        $adapter->commit();
        $this->assertTrue($adapter->hasItem('unit.test'));
    }

    public function testSave(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    public function testSaveItemWithValue(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');

        $item->set('item.value');
        $adapter->save($item);

        $this->assertSame('item.value', $adapter->getItem('unit.test')->get());
    }

    public function testSaveDeferred(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
