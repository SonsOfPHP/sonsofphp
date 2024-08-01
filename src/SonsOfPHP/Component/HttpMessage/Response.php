<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

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
        if (!Status::tryFrom($code) instanceof Status) {
            throw new InvalidArgumentException(sprintf('The status code "%d" is invalid', $code));
        }

        if (isset($this->statusCode) && $this->statusCode === $code && $this->reasonPhrase === $reasonPhrase) {
            return $this;
        }

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
