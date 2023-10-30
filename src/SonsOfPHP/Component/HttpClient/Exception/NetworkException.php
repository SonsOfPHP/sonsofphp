<?php

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NetworkException extends ClientException implements NetworkExceptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
