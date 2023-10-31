<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpFactory\RequestFactory;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Client implements ClientInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(
        private HandlerInterface $handler = new HandlerStack(),
        private RequestFactoryInterface $requestFactory = new RequestFactory(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }

    public function head(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('HEAD', $uri));
    }

    public function get(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('GET', $uri));
    }

    public function post(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('POST', $uri));
    }

    public function put(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('PUT', $uri));
    }

    public function patch(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('PATCH', $uri));
    }

    public function delete(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('DELETE', $uri));
    }

    public function purge(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('PURGE', $uri));
    }

    public function options(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('OPTIONS', $uri));
    }

    public function trace(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('TRACE', $uri));
    }

    public function connect(UriInterface|string $uri): ResponseInterface
    {
        return $this->sendRequest($this->requestFactory->createRequest('CONNECT', $uri));
    }
}
