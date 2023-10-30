<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpMessage\ServerRequest;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    public function __construct(
        private UriFactoryInterface $uriFactory = new UriFactory(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function createServerRequest(string $method, UriInterface|string $uri, array $serverParams = []): ServerRequestInterface
    {
        if (is_string($uri)) {
            $uri = $uriFactory->createUri($uri);
        }

        // Note to future self: There is no method "withServerParams"
        return (new ServerRequest())->withMethod($method)->withUri($uri)->withServerParams($serverParams);
    }
}
