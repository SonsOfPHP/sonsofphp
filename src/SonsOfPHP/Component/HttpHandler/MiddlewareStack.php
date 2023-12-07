<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler;

use Psr\Http\Server\MiddlewareInterface;
use SonsOfPHP\Contract\HttpHandler\MiddlewareStackInterface;
use SonsOfPHP\Component\HttpHandler\Exception\HttpHandlerException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MiddlewareStack implements MiddlewareStackInterface
{
    private array $middlewares = [];
    //private $resolver;

    //public function __construct($resolver)
    //{
    //    $this->resolver = $resolver;
    //}

    /**
     * Adds a new middleware to the stack. Middlewares can be prioritized and
     * will be ordered from the lowest number to the highest number (ascending
     * order).
     */
    public function add(MiddlewareInterface|\Closure $middleware, int $priority = 0): self
    {
        $this->middlewares[$priority][] = $middleware;
        ksort($this->middlewares);

        return $this;
    }

    public function next(): MiddlewareInterface
    {
        $priorityStack = array_shift($this->middlewares);
        $middleware = array_shift($priorityStack);
        if (0 !== count($priorityStack)) {
            array_shift($this->middlewares, $priorityStack);
        }

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

        //if (is_string($middleware)) {
        //    // use the resolver to figure out wtf this is
        //    return $this->resolver($middleware);
        //}

        throw new HttpHandlerException('Unknown Middleware Type: ' . gettype($middleware));
    }

    public function count(): int
    {
        return count($this->middlewares);
    }
}
