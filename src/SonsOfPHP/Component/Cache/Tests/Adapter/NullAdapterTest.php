<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Adapter\NullAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\Adapter\NullAdapter
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @uses \SonsOfPHP\Component\Cache\Adapter\NullAdapter
 */
final class NullAdapterTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new NullAdapter();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    /**
     * @covers ::getItem
     */
    public function testGetItem(): void
    {
        $adapter = new NullAdapter();
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    /**
     * @covers ::getItems
     */
    public function testGetItems(): void
    {
        $adapter = new NullAdapter();
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
        $adapter = new NullAdapter();

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    /**
     * @covers ::clear
     */
    public function testClear(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->clear());
    }

    /**
     * @covers ::deleteItem
     */
    public function testDeleteHasItem(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->deleteItem('item.key'));
    }

    /**
     * @covers ::deleteItems
     */
    public function testDeleteHasItems(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->deleteItems(['item.key']));
    }

    /**
     * @covers ::commit
     */
    public function testCommit(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->commit());
    }

    /**
     * @covers ::save
     */
    public function testSave(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    /**
     * @covers ::saveDeferred
     */
    public function testSaveDeferred(): void
    {
        $adapter = new NullAdapter();

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
