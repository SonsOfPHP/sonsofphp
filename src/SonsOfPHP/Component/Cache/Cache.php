<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\SimpleCache\CacheInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Simple Cache
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Cache implements CacheInterface
{
    public function __construct(
        private CacheItemPoolInterface $pool,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $item = $this->pool->getItem($key);

        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        $item = $this->pool->getItem($key);
        $item->set($value);

        if (null !== $ttl) {
            $item->expiresAfter($ttl);
        }

        return $this->pool->save($item);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): bool
    {
        return $this->pool->deleteItem($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        return $this->pool->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $items = $this->pool->getItems($keys);
        foreach ($items as $item) {
            yield $key => $item->isHit() ? $item->get() : $default;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return $this->pool->deleteItems(iterator_to_array($keys));
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return $this->pool->hasItem($key);
    }
}
