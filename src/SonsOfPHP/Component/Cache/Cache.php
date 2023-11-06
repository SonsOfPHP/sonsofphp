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
}
