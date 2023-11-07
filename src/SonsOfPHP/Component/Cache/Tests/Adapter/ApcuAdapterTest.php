<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\Adapter\ApcuAdapter
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @uses \SonsOfPHP\Component\Cache\Adapter\ApcuAdapter
 */
final class ApcuAdapterTest extends TestCase
{
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
    public function testDeleteItems(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->deleteItems(['item.key']));
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
     * @covers ::save
     */
    public function testSave(): void
    {
        $adapter = new ApcuAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
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
