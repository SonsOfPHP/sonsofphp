<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Message implements MessageInterface
{
    public const DEFAULT_PROTOCOL_VERSION = '1.1';

    private string $protocolVersion = self::DEFAULT_PROTOCOL_VERSION;
    private array $headers = [];
    private array $originalHeaders = [];
    private StreamInterface $body;

    /**
     * {@inheritdoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $that = clone $this;

        $that->protocolVersion = $version;

        return $that;
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
    public function hasHeader(string $name): bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name): array
    {
        if ($this->hasHeader($name)) {
            return $this->headers[$name];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderLine(string $name): string
    {
        if (!$this->hasHeader($name)) {
            return '';
        }

        return implode(', ', $this->getHeader($name));
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        // @todo throw \InvalidArgumentException for invalid header names or values.

        $that = clone $this;

        if (is_string($value)) {
            $value = [$value];
        }
        $that->originalHeaders[$name] = $value;

        $values = $value;
        array_walk($values, function (&$val, $key): void {
            $val = strtolower($val);
        });
        $that->headers[strtolower($name)] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // @todo \InvalidArgumentException for invalid header names or values.
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        if (is_string($value)) {
            $value = [$value];
        }

        $that = clone $this;
        $that->originalHeaders[$name][] = $value;

        $values = $value;
        array_walk($values, function (&$val, $key): void {
            $val = strtolower($val);
        });
        $that->headers[strtolower($name)][] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $that = clone $this;
        if (!$that->hasHeader($name)) {
            return $that;
        }

        unset($that->headers[strtolower($name)]);

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        // @todo \InvalidArgumentException When the body is not valid.
        $that = clone $this;

        $that->body = $body;

        return $that;
    }
}
