<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Message\RequestInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface RequestMiddlewareInterface
{
    public function __invoke(RequestInterface $request, HandlerInterface $handler): RequestInterface;
}
