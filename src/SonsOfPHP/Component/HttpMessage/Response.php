<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpMessage\HttpMessageException;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Response extends Message implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase = '';

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $that = clone $this;

        $that->statusCode   = $code;
        $that->reasonPhrase = $reasonPhrase;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
