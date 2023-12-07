<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Mailer;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageInterface
{
    /**
     * Returns the contents of the message
     *
     * If there has been no body set, this will return null
     */
    public function getBody(): ?string;

    /**
     * This will set the body contents, if there is already a body, this will
     * overwrite that content
     */
    public function setBody(string $body): self;

    /**
     * Returns headers in key => value format
     */
    public function getHeaders(): array;

    /**
     * If the header is not found or has no value, this will return null
     *
     * When getting a header, the header name MUST NOT be case sensitive
     *
     * Example:
     *   $from = $message->getHeader('From');
     */
    public function getHeader(string $name): ?string;

    /**
     * If a header is set, this will return true
     */
    public function hasHeader(string $name): bool;

    /**
     * If the header already exists, this will append the new value
     *
     * If the header does not already exist, this will add it
     *
     * @throws \InvalidArgumentException
     *   If name or value is invalid
     */
    public function addHeader(string $name, AddressInterface|string $value): self;
}
