<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Exception;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NetworkException extends ClientException implements NetworkExceptionInterface
{
    public function __construct(
        string $message,
        private RequestInterface $request,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
