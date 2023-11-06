<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\SimpleCache\CacheInterface;
use SonsOfPHP\Component\Cache\Adapter\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Cache implements CacheInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    /**
     * {@inheritdoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $item = $this->adapter->getItem($key);

        return $item->isHit() ? $item->get() : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        $item = $this->adapter->getItem($key);
        $item->set($value);

        if (null !== $ttl) {
            $item->expiresAfter($ttl);
        }

        return $this->adapter->save($item);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): bool
    {
        return $this->adapter->deleteItem($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        return $this->adapter->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $items = $this->adapter->getItems($keys);
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
        return $this->adapter->deleteItems(iterator_to_array($keys));
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return $this->adapter->hasItem($key);
    }
}
