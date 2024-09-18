<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Tests\Adapter;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use SonsOfPHP\Component\Cache\Adapter\AbstractAdapter;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;
use SonsOfPHP\Component\Cache\Adapter\FilesystemAdapter;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Marshaller\SerializableMarshaller;

#[CoversClass(FilesystemAdapter::class)]
#[UsesClass(CacheItem::class)]
#[UsesClass(AbstractAdapter::class)]
#[UsesClass(SerializableMarshaller::class)]
final class FilesystemAdapterTest extends TestCase
{
    private FilesystemAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new FilesystemAdapter();
    }

    protected function tearDown(): void
    {
        $this->adapter->clear();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(AdapterInterface::class, $this->adapter);
        $this->assertInstanceOf(CacheItemPoolInterface::class, $this->adapter);
    }

    public function testItCanGetItemWhenCold(): void
    {
        $cacheItem = $this->adapter->getItem('test');
        $this->assertFalse($cacheItem->isHit());
    }

    public function testItIsAbleToSaveCacheItem(): void
    {
        $cacheItem = $this->adapter->getItem('test');
        $this->assertFalse($cacheItem->isHit());
        $this->adapter->save($cacheItem->set('just a test')->expiresAfter(60));

        $cacheItem = $this->adapter->getItem('test');
        $this->assertTrue($cacheItem->isHit());
        $this->assertSame('just a test', $cacheItem->get());
    }

    public function testItCanDeleteCacheItem(): void
    {
        $cacheItem = $this->adapter->getItem('test');
        $this->adapter->save($cacheItem->expiresAfter(60));
        $this->assertTrue($this->adapter->hasItem('test'));
        $this->assertTrue($this->adapter->deleteItem('test'));
        $this->assertFalse($this->adapter->hasItem('test'));
    }

    public function testItWillReturnFalseWhenDeletingKeyThatDoesNotExist(): void
    {
        $this->assertFalse($this->adapter->deleteItem('test'));
    }
}
