<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpMessage\Uri;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UriFactory implements UriFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
