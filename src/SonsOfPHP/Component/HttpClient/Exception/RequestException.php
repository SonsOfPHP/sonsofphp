<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class RequestException extends ClientException implements RequestExceptionInterface
{
    public function __construct(
        string $message,
        private RequestInterface $request,
        private ?ResponseInterface $response = null,
        \Throwable $previous = null,
    ) {
        $code = $response ? $response->getStatusCode() : 0;
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
