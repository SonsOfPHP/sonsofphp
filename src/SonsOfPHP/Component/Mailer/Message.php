<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\AddressInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Message implements MessageInterface
{
    private array $headers = [];
    private string $body;

    /**
     * {@inheritdoc}
     */
    public function getBody(): ?string
    {
        return $this->body ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(string $name, AddressInterface|string $value): self
    {
        if ($this->hasHeader($name)) {
            $value = $this->headers[strtolower($name)] . ', ' . $value;
        }

        $this->headers[strtolower($name)] = $value;

        return $this;
    }
}
