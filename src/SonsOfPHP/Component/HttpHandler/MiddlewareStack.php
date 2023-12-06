<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler;

use Psr\Http\Server\MiddlewareInterface;

// @todo implement interface (make contract)
class MiddlewareStack
{
    private array $middlewares = [];
    private $resolver;

    public function __construct($resolver)
    {
        $this->resolver = $resolver;
    }

    // @todo Set Priorities
    public function add($middleware): self
    {
        // $this->middlewares[$priority][] = $middleware;
        // ksort($this->middlewares);
        $this->middlewares[] = $middleware;

        return $this;
    }

    public function shift(): MiddlewareInterface
    {
        $middleware = array_shift($this->middlewares);

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        if ($middleware instanceof \Closure) {
            return new class($middleware) implements MiddlewareInterface {
                public function __construct(private \Closure $closure) {}

                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
                {
                    return $this->closure($request, $handler);
                }
            };
        }

        if (is_string($middleware)) {
            // use the resolver to figure out wtf this is
            return $this->resolver($middleware);
        }

        throw new \Exception('Unknown Middleware Type: ' . gettype($middleware));
    }

    public function count(): int
    {
        return count($this->middlewares);
    }
}
