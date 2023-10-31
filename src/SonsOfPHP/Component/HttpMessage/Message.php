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
    private array $normalizedHeaders = [];
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
        return array_key_exists(strtolower($name), $this->normalizedHeaders);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name): array
    {
        if ($this->hasHeader($name)) {
            return $this->normalizedHeaders[strtolower($name)];
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
        if (!preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/D', $name)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid header name.', $name));
        }

        $that = clone $this;

        if (!is_array($value)) {
            $value = [$value];
        }
        $that->normalizedHeaders[$name] = $value;

        array_walk($value, function (&$val, $key): void {
            if (is_string($val)) {
                $val = strtolower($val);
            }
        });
        $that->headers[strtolower($name)] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        if (!preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/D', $name)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not valid header name.', $name));
        }

        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        if (is_string($value)) {
            $value = [$value];
        }

        $that = clone $this;
        $that->normalizedHeaders[$name][] = $value;

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
        if (!$this->hasHeader($name)) {
            return $this;
        }

        $that = clone $this;
        foreach ($this->headers as $header => $values) {
            if (0 === strcasecmp($header, $name)) {
                unset(
                    $that->headers[$header],
                    $that->normalizedHeaders[strtolower($name)]
                );
                break;
            }
        }

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
