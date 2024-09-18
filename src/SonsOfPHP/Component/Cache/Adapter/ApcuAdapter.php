<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use Psr\Cache\CacheItemInterface;
use SonsOfPHP\Component\Cache\CacheItem;
use SonsOfPHP\Component\Cache\Exception\CacheException;
use SonsOfPHP\Component\Cache\Marshaller\MarshallerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ApcuAdapter extends AbstractAdapter
{
    public function __construct(
        int $defaultTTL = 0,
        ?MarshallerInterface $marshaller = null,
    ) {
        if (!extension_loaded('apcu') || !filter_var(ini_get('apc.enabled'), FILTER_VALIDATE_BOOL) || false === apcu_enabled()) {
            throw new CacheException('APCu extension is required.');
        }

        parent::__construct(
            defaultTTL: $defaultTTL,
            marshaller: $marshaller,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(string $key): CacheItemInterface
    {
        if ($this->hasItem($key)) {
            return (new CacheItem($key, true))
                ->set($this->marshaller->unmarshall(apcu_fetch($key)))
            ;
        }

        return new CacheItem($key, false);
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
    public function save(CacheItemInterface $item): bool
    {
        return apcu_store($item->getKey(), $this->marshaller->marshall($item->get()), 0);
    }
}
