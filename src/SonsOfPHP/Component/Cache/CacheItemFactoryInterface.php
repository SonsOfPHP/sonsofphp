<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CacheItemFactroyInterface
{
    /**
     */
    public function createCacheItem(string $key, mixed $value, bool $isHit = false): CacheItemInterface;
}
