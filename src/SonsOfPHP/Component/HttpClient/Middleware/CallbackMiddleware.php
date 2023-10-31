<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CallbackMiddleware implements MiddlewareInterface
{
    public function __construct(
        private $func,
    ) {
        if (!is_callable($func)) {
            throw new \InvalidArgumentException('Must be callable');
        }

        $this->func = $func;
    }

    public function process(HandlerInterface $handler, RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        return call_user_func($this->func, $handler, $request, $response);
    }
}
