<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Handler;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CurlHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        return $response;
    }
}
