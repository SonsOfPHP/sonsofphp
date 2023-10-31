<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Test;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpMessage\Request;

/**
 * The NullHandler will just pass-thru the response. If there is no
 * $response passed in, it will create a default one.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        if (null === $response) {
            $response = new Response();
        }

        return $response;
    }
}
