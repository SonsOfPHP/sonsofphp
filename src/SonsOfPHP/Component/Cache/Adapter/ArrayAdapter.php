<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\CacheItem;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ArrayAdapter implements AdapterInterface
{
    private array $values = [];

    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        if ($this->hasItem($key)) {
            return (new CacheItem($key, true))->set($this->values[$key]);
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

        return array_key_exists($key, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        $this->values = [];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem(string $key): bool
    {
        CacheItem::validateKey($key);

        unset($this->values[$key]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        $this->values[$item->getKey()] = $item->get();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->save($item);
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        return true;
    }
}
