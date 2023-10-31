<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Test;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MockHandler implements HandlerInterface
{
    public function __construct(
        private array $responses = [],
    ) {}

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request, ?ResponseInterface $response = null): ResponseInterface
    {
        if (0 === count($this->responses)) {
            throw new \RuntimeException('No Responses have been set');
        }

        return array_shift($this->response);
    }
}
