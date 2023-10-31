<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Request extends Message implements RequestInterface
{
    private ?string $requestTarget = null;
    private UriInterface $uri;

    public function __construct(
        private ?string $method = null,
        UriInterface|string $uri = null,
    ) {
        if (null !== $method && null === Method::tryFrom(strtoupper($method))) {
            throw new \InvalidArgumentException(sprintf('The value of "%s" for $method is invalid', strtoupper($method)));
        }

        if (is_string($uri)) {
            $uri = new Uri($uri);
        }

        if (null !== $uri) {
            $this->uri = $uri;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if ($requestTarget === $this->requestTarget) {
            return $this;
        }

        $that = clone $this;

        $that->requestTarget = $requestTarget;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod(string $method): RequestInterface
    {
        if (null !== $method && null === Method::tryFrom(strtoupper($method))) {
            throw new \InvalidArgumentException(sprintf('The value of "%s" for $method is invalid', strtoupper($method)));
        }

        if ($method === $this->method) {
            return $this;
        }

        $that = clone $this;

        $that->method = $method;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        if (isset($this->uri) && (string) $uri === (string) $this->uri) {
            return $this;
        }

        $that = clone $this;
        $that->uri = $uri;

        if (true === $preserveHost && $this->hasHeader('host')) {
            $that = $that->withHeader('host', $this->getHeader('host'));
        }

        return $that;
    }
}
