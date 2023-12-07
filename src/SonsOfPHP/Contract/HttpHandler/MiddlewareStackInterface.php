<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\HttpHandler;

use Psr\Http\Server\MiddlewareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MiddlewareStackInterface extends \Countable
{
    /**
     * Returns the next Middleware in the stack
     *
     * @throws \SonsOfPHP\Contract\HttpHandler\HttpHandlerExceptionInterface
     *   When there is some type of error
     */
    public function next(): MiddlewareInterface;
}
