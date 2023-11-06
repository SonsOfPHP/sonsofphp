<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CacheItemFactroy implements CacheItemFactroyInterface
{
    public function createCacheItem(string $key, mixed $value, bool $isHit = false): CacheItemInterface
    {
        $item = new CacheItem($key, $isHit);
        $item->set($value);

        return $item;
    }
}
