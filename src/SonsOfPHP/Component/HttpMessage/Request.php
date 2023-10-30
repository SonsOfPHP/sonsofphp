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
    public const METHOD_HEAD    = 'HEAD';
    public const METHOD_GET     = 'GET';
    public const METHOD_POST    = 'POST';
    public const METHOD_PUT     = 'PUT';
    public const METHOD_PATCH   = 'PATCH';
    public const METHOD_DELETE  = 'DELETE';
    public const METHOD_PURGE   = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE   = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    private string $requestTarget;
    private UriInterface $uri;

    public function __construct(
        private ?string $method = null,
        UriInterface|string $uri = null,
    ) {
        // @todo throw exception if method invalid

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
        return $this->requestTarget;
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // @todo if requestTarget is same, do not clone

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
        // @todo throw exception if method is invliad
        // @todo if method is same, do not clone

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
        // @todo implement perserveHost
        // @todo if uri is same, do not clone

        $that = clone $this;

        $that->uri = $uri;

        return $that;
    }
}
