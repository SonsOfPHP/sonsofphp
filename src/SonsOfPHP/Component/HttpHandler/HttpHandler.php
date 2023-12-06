<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// RequestHandler?
class HttpHandler implements RequestHandlerInterface
{
    public function __construct(private array $queue = []) {}

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (0 === count($this->queue)) {
            throw new \Exception('No Middleware in the queue.');
        }

        $middleware = array_shift($this->queue);

        return $middleware->process($request, $this);
    }
}
