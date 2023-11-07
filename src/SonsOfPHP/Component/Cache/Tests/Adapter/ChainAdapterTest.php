<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cache\Adapter\ChainAdapter;
use SonsOfPHP\Component\Cache\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\Exception\CacheException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cache\Adapter\ChainAdapter
 *
 * @uses \SonsOfPHP\Component\Cache\CacheItem
 * @uses \SonsOfPHP\Component\Cache\Adapter\ChainAdapter
 * @uses \SonsOfPHP\Component\Cache\Adapter\ArrayAdapter
 */
final class ChainAdapterTest extends TestCase
{
    private $adapters = [];

    public function setUp(): void
    {
        $this->adapters[] = $this->createMock(AdapterInterface::class);
        $this->adapters[] = new ArrayAdapter();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWhenInvalidAdapter(): void
    {
        $this->adapters[] = new \stdClass();

        $this->expectException(CacheException::class);
        $adapter = new ChainAdapter($this->adapters);
    }

    /**
     * @covers ::getItem
     */
    public function testGetItem(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    /**
     * @covers ::getItem
     */
    public function testGetItemAfterSave(): void
    {
        $adapter = new ChainAdapter($this->adapters);
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
        $adapter = new ChainAdapter($this->adapters);
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
        $adapter = new ChainAdapter($this->adapters);

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    /**
     * @covers ::hasItem
     */
    public function testHasItemWhenOneHasKey(): void
    {
        // Create new mock adapter and place at end of stack
        $mock = $this->createMock(AdapterInterface::class);
        $mock->expects($this->once())->method('hasItem')->willReturn(true);
        $this->adapters[] = $mock;

        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->hasItem('item.key'));
    }

    /**
     * @covers ::clear
     */
    public function testClear(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->clear());
    }

    /**
     * @covers ::deleteItem
     */
    public function testDeleteHasItem(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->deleteItem('item.key'));
    }

    /**
     * @covers ::deleteItems
     */
    public function testDeleteHasItems(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->deleteItems(['item.key']));
    }

    /**
     * @covers ::commit
     */
    public function testCommit(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->commit());
    }

    /**
     * @covers ::save
     */
    public function testSave(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    /**
     * @covers ::saveDeferred
     */
    public function testSaveDeferred(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
