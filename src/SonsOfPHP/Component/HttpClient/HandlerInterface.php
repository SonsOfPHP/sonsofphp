<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Used to manage middlewares
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface HandlerInterface
{
    public function handle(RequestInterface $request, ResponseInterface $response): ResponseInterface;
}
