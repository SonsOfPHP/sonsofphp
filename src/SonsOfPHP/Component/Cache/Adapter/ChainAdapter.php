<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use SonsOfPHP\Component\Cache\CacheItem;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\Exception\CacheException;

/**
 * Chains multiple adapters together to allow for multi-layer caches
 *
 * This will write to all, but only pull from the first adapter
 *
 * Usage:
 *   $adapter = new ChainAdapter([
 *      new ArrayAdapter(),
 *      new ApcuAdapter(),
 *      new NativeFilesystemAdapter(),
 *      new RedisAdapter(),
 *   ]);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ChainAdapter implements AdapterInterface
{
    public function __construct(
        private array $adapters,
    ) {
        foreach ($this->adapters as $adapter) {
            if (!$adapter instanceof AdapterInterface) {
                throw new CacheException('Invalid Adapter, must implement AdapterInterface');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        CacheItem::validateKey($key);

        foreach ($this->adapters as $adapter) {
            if ($adapter->hasItem($key)) {
                return $adapter->getItem($key);
            }
        }

        return new CacheItem($key, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = []): iterable
    {
        foreach ($keys as $key) {
            yield $key => $this->getItem($key);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(string $key): bool
    {
        CacheItem::validateKey($key);

        foreach ($this->adapters as $adapter) {
            if ($adapter->hasItem($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        foreach ($this->adapters as $adapter) {
            $adapter->clear();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem(string $key): bool
    {
        CacheItem::validateKey($key);

        foreach ($this->adapters as $adapter) {
            $adapter->deleteItem($key);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        foreach ($this->adapters as $adapter) {
            $adapter->deleteItems($keys);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        foreach ($this->adapters as $adapter) {
            $adapter->save($item);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        foreach ($this->adapters as $adapter) {
            $adapter->saveDeferred($item);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        foreach ($this->adapters as $adapter) {
            $adapter->commit();
        }

        return true;
    }
}
