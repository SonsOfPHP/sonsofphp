<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\Exception\ClientException;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;

;
use SonsOfPHP\Component\HttpClient\HandlerInterface;

;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HttpErrorMiddleware implements MiddlewareInterface
{
    /**
     */
    public function process(HandlerInterface $handler, RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        if (null === $response) {
            return $handler->handle($request, $response);
        }

        if (400 >= $response->getStatusCode()) {
            throw new ClientException('Error with Request', $response->getStatusCode());
        }
    }
}
