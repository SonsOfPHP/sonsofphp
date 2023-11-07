<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Adapter;

use Psr\Cache\CacheItemPoolInterface;

/**
 * All Adapters MUST implement this interface
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AdapterInterface extends CacheItemPoolInterface {}
