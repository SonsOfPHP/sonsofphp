<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpClient\Handler\CurlHandler;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HandlerStack implements HandlerInterface
{
    private array $stack = [];

    public function __construct(
        private HandlerInterface $handler = new CurlHandler(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        if (0 === $this->count()) {
            if (null !== $response) {
                return $response;
            }

            throw new \RuntimeException('The request was not handled.');
        }

        return $this->shift()->process($this->handler, $request, $response);
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
        $this->stack[$idx] = $middleware;
    }

    private function shift(): MiddlewareInterface
    {
        return array_shift($this->stack);
    }

    private function count(): int
    {
        return count($this->stack);
    }
}
