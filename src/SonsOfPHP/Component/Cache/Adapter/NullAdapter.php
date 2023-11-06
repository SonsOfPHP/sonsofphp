<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        return new CacheItem($key, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = []): iterable
    {
        foreach ($keys as $key) {
            yield $this->getItem($key);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(string $key): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem(string $key): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        return true;
    }
}
