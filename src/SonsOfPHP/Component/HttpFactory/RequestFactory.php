<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpMessage\Request;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class RequestFactory implements RequestFactoryInterface
{
    public function __construct(
        private UriFactoryInterface $uriFactory = new UriFactory(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function createRequest(string $method, UriInterface|string $uri): RequestInterface
    {
        if (is_string($uri)) {
            $uri = $uriFactory->createUri($uri);
        }

        return (new Request())->withMethod($method)->withUri($uri);
    }
}
