<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Exception;

use Exception;
use Psr\Cache\CacheException as CacheExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CacheException extends Exception implements CacheExceptionInterface {}
