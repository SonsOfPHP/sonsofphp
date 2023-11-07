<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use SonsOfPHP\Component\Cache\CacheItem;
use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\Exception\CacheException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ApcuAdapter implements AdapterInterface
{
    private array $deferred = [];

    public function __construct()
    {
        if (!extension_loaded('apcu') || !filter_var(ini_get('apc.enabled'), FILTER_VALIDATE_BOOL) || false === apcu_enabled()) {
            throw new CacheException('APCu extension is required.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        if ($this->hasItem($key)) {
            return (new CacheItem($key, true))->set(apcu_fetch($key));
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

        return apcu_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        return apcu_clear_cache();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem(string $key): bool
    {
        CacheItem::validateKey($key);

        return apcu_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        $ret = true;
        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $ret = false;
            }
        }

        return $ret;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        $this->saveDeferred($item);

        return $this->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        foreach ($this->deferred as $key => $item) {
            apcu_store($key, $item->get(), 0);
        }
        $this->deferred = [];

        return true;
    }
}
