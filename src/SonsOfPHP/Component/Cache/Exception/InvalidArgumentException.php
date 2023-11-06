<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cache\Exception;

use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements InvalidArgumentExceptionInterface {}
