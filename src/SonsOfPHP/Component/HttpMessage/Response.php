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
    private int $statusCode = 200;

    private string $reasonPhrase = '';

    public function __construct(int $statusCode = 200, string $reasonPhrase = '')
    {
        if (!Status::tryFrom($statusCode) instanceof Status) {
            throw new InvalidArgumentException(sprintf('The status code "%d" is invalid', $statusCode));
        }

        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

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

        if ($this->statusCode === $code && $this->reasonPhrase === $reasonPhrase) {
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
