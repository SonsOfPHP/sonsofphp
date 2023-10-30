<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Client implements ClientInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory = new ResponseFactory(),
    ) {}

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponse();
    }
}
