<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface ClientHandlerInterface
{
    /**
     */
    public function handle(RequestInterface $request): ResponseInterface;
}
