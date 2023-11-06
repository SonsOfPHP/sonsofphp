<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface TaggableCacheItem extends CacheItemInterface
{
    public function setTags(array $tags);
}
