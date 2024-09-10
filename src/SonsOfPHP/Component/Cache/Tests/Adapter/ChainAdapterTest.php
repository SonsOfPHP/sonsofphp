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
use SonsOfPHP\Component\Cache\Adapter\ChainAdapter;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Exception\CacheException;
use stdClass;

#[CoversClass(ChainAdapter::class)]
#[UsesClass(ArrayAdapter::class)]
#[UsesClass(CacheItem::class)]
final class ChainAdapterTest extends TestCase
{
    private array $adapters = [];

    protected function setUp(): void
    {
        $this->adapters[] = $this->createMock(AdapterInterface::class);
        $this->adapters[] = new ArrayAdapter();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $adapter);
    }

    public function testConstructWhenInvalidAdapter(): void
    {
        $this->adapters[] = new stdClass();

        $this->expectException(CacheException::class);
        new ChainAdapter($this->adapters);
    }

    public function testGetItem(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $item = $adapter->getItem('unit.test');

        $this->assertInstanceOf(CacheItemInterface::class, $item);
    }

    public function testGetItemAfterSave(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $item = $adapter->getItem('unit.test');
        $item->set('item.value');

        $adapter->save($item);

        $this->assertTrue($adapter->getItem('unit.test')->isHit());
    }

    public function testGetItems(): void
    {
        $adapter = new ChainAdapter($this->adapters);
        $items = $adapter->getItems(['unit.test']);
        foreach ($items as $key => $item) {
            $this->assertSame('unit.test', $key);
        }
    }

    public function testHasItem(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertFalse($adapter->hasItem('item.key'));
    }

    public function testHasItemWhenOneHasKey(): void
    {
        // Create new mock adapter and place at end of stack
        $mock = $this->createMock(AdapterInterface::class);
        $mock->expects($this->once())->method('hasItem')->willReturn(true);
        $this->adapters[] = $mock;

        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->hasItem('item.key'));
    }

    public function testClear(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->clear());
    }

    public function testDeleteHasItem(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->deleteItem('item.key'));
    }

    public function testDeleteHasItems(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->deleteItems(['item.key']));
    }

    public function testCommit(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->commit());
    }

    public function testSave(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->save($this->createMock(CacheItemInterface::class)));
    }

    public function testSaveDeferred(): void
    {
        $adapter = new ChainAdapter($this->adapters);

        $this->assertTrue($adapter->saveDeferred($this->createMock(CacheItemInterface::class)));
    }
}
