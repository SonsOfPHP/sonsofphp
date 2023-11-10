<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs\Exception;

/**
 * If a handler has not been registered or cannot be found, this exception
 * would be used.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface NoHandlerFoundExceptionInterface extends CqrsExceptionInterface {}
