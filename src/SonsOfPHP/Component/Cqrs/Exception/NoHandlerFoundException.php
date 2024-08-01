<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Exception;

use Exception;
use SonsOfPHP\Contract\Cqrs\Exception\NoHandlerFoundExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NoHandlerFoundException extends Exception implements NoHandlerFoundExceptionInterface {}
