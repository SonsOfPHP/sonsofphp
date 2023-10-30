<?php

namespace SonsOfPHP\Component\HttpClient;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class RequestException extends ClientException implements RequestExceptionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
