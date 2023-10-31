<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ResponseMiddlewareInterface
{
    public function __invoke(ResponseInterface $request, HandlerInterface $handler): ResponseInterface;
}
