<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CacheItemFactory implements CacheItemFactoryInterface
{
    public function createCacheItem(string $key, mixed $value, bool $hit = false, int $ttl = 0): CacheItemInterface
    {
        $item = new CacheItem($key, $hit);
        $item->set($value);
        $item->expiresAfter($ttl);

        return $item;
    }
}
