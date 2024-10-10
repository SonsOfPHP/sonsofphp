<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use SonsOfPHP\Component\Cache\Adapter\AbstractAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\CacheItem;

#[CoversClass(AbstractAdapter::class)]
#[UsesClass(CacheItem::class)]
final class AbstractAdapterTest extends TestCase
{
    private AbstractAdapter $adapter;

    protected function setUp(): void
    {
        //$this->adapter = $this->createStub(AbstractAdapter::class);
        $this->adapter = new class extends AbstractAdapter {
            public function getItem(string $key): CacheItemInterface
            {
                return new CacheItem($key, false);
            }

            public function hasItem(string $key): bool
            {
                return $this->getItem($key)->isHit();
            }

            public function clear(): bool
            {
                return true;
            }

            public function deleteItem(string $key): bool
            {
                return true;
            }

            public function save(CacheItemInterface $item): bool
            {
                return true;
            }
        };
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $this->adapter);
    }

    public function testItWillExecuteGetItemWhenGetItemsIsExecuted(): void
    {
        $items = iterator_to_array($this->adapter->getItems(['test']));
        $this->assertArrayHasKey('test', $items);
    }

    public function testItWillDeleteItems(): void
    {
        $this->assertTrue($this->adapter->deleteItems(['test']));
    }

    public function testItWillSaveDeferred(): void
    {
        $cacheItem = $this->adapter->getItem('test');

        $this->assertTrue($this->adapter->saveDeferred($cacheItem));
    }

    public function testItWillCommit(): void
    {
        $cacheItem = $this->adapter->getItem('test');
        $this->adapter->saveDeferred($cacheItem);

        $this->assertTrue($this->adapter->commit());
    }

    public function testItWillDestruct(): void
    {
        $cacheItem = $this->adapter->getItem('test');
        $this->adapter->saveDeferred($cacheItem);

        $this->assertTrue($this->adapter->commit());
        unset($this->adapter);
    }
}
