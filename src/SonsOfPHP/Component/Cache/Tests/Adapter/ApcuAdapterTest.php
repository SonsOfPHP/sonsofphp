<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

/**
 * @requires extension apcu
 *
 * @coversDefaultClass \SonsOfPHP\Component\Cache\Adapter\ApcuAdapter
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @uses \SonsOfPHP\Component\Cache\Adapter\ApcuAdapter
 */
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

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    /**
     * @covers ::getItem
     */
    public function testGetItem(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    /**
     * @covers ::getItem
     */
    public function testGetItemAfterSave(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->save($item);

        $this->assertTrue($adapter->getItem('unit.test')->isHit());
    }

    /**
     * @covers ::getItems
     */
    public function testGetItems(): void
    {
        $adapter = new ApcuAdapter();
        $items = $adapter->getItems(['unit.test']);
        foreach ($items as $key => $item) {
            $this->assertSame('unit.test', $key);
        }
    }

    /**
     * @covers ::hasItem
     */
    public function testHasItem(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    /**
     * @covers ::clear
     */
    public function testClear(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->clear());
    }

    /**
     * @covers ::deleteItem
     */
    public function testDeleteItem(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->deleteItem('item.key'));
    }

    /**
     * @covers ::deleteItems
     */
    public function testDeleteItemsWithValuesInCache(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');
        $adapter->save($item);

        $this->assertTrue($adapter->deleteItems(['unit.test']));
    }

    /**
     * @covers ::deleteItems
     */
    public function testDeleteItemsWithEmptyCache(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertFalse($adapter->deleteItems(['item.key']));
    }

    /**
     * @covers ::commit
     */
    public function testCommit(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->commit());
    }

    /**
     * @covers ::commit
     */
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

    /**
     * @covers ::save
     */
    public function testSave(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    /**
     * @covers ::save
     */
    public function testSaveItemWithValue(): void
    {
        $adapter = new ApcuAdapter();
        $item = $adapter->getItem('unit.test');

        $item->set('item.value');
        $adapter->save($item);

        $this->assertSame('item.value', $adapter->getItem('unit.test')->get());
    }

    /**
     * @covers ::saveDeferred
     */
    public function testSaveDeferred(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
