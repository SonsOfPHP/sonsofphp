<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpClient\Handler\CurlHandler;
use SonsOfPHP\Component\HttpClient\ResponseMiddlewareInterface;
use SonsOfPHP\Component\HttpClient\RequestMiddlewareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class HandlerStack implements HandlerInterface
{
    private array $requestStack  = [];
    private array $responseStack = [];
    private bool $isHandled = false;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private HandlerInterface $handler = new CurlHandler(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        // 1) Process all request middleware (OAuth, etc)
        // 2) handle request, return result
        // 3) process all response middleware (Handle Errors, attach metadata)
        // 4) return response

        if (0 === count($this->requestStack) && false === $this->isHandled) {
            $this->isHandled = true;
            $response = $this->handler->handle($request, $response);
        }

        if (0 !== count($this->requestStack)) {
            $middleware = array_shift($this->requestStack);

            $response = $middleware->process($this, $request, $response);
        }

        if (0 !== count($this->responseStack)) {
            $middleware = array_shift($this->responseStack);

            $response = $middleware->process($this, $request, $response);
        }

        return $response;
    }

    public function push(MiddlewareInterface $middleware, ?string $name = null): void
    {
        if (null === $name) {
            $name = $middleware::class;
        }

        $this->add($name, $middleware);
    }

    private function add(string $idx, MiddlewareInterface $middleware): void
    {
        $this->requestStack[$idx]  = $middleware;

        $this->responseStack = array_reverse($this->requestStack, true);
    }
}
