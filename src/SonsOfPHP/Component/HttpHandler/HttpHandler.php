<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class HttpHandler implements RequestHandlerInterface
{
    public function __construct(private MiddlewareStack $stack) {}

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (0 === $this->stack->count()) {
            throw new \Exception('No Middleware in the queue.');
        }

        $middleware = $this->stack->next();

        return $middleware->process($request, $this);
    }
}
