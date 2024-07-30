<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\HttpHandler;

use Countable;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MiddlewareStackInterface extends Countable
{
    /**
     * Returns the next Middleware in the stack
     *
     * @throws HttpHandlerExceptionInterface
     *   When there is some type of error
     */
    public function next(): MiddlewareInterface;
}
