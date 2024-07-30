<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Stringable;
use Throwable;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Stream implements StreamInterface, Stringable
{
    /**
     * @param resource|null $stream
     *   If no resource is passed, it will use php://temp
     */
    public function __construct(protected $stream = null)
    {
        if (null !== $stream && !is_resource($stream)) {
            throw new InvalidArgumentException('Only a resource and null are supported.');
        }

        if (!is_resource($stream)) {
            $stream = fopen('php://memory', 'w+');
        }

        $this->stream = $stream;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (Throwable) {
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        if (null !== $this->stream && is_resource($this->stream)) {
            fclose($this->stream);
            $this->stream = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        if (null === $this->stream) {
            return null;
        }

        $stream       = $this->stream;
        $this->stream = null;

        return $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        if (null === $this->stream) {
            return null;
        }

        $stats = fstat($this->stream);
        if (array_key_exists('size', $stats)) {
            return $stats['size'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        if (false === $position = ftell($this->stream)) {
            throw new RuntimeException('Unable to figure out the current position');
        }

        return $position;
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        return feof($this->stream);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        if (null === $seekable = $this->getMetadata('seekable')) {
            return false;
        }

        return $seekable;
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        if (false === $this->isSeekable()) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (-1 === fseek($this->stream, $offset, $whence)) {
            throw new RuntimeException('Unable to seek');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        return match($this->getMetadata('mode')) {
            'w+b', 'a+b',
            'a', 'w', 'r+', 'rb+', 'rw', 'x', 'c' => true,
            default => false,
        };
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string): int
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        if (false === $this->isWritable()) {
            throw new RuntimeException('Stream is un-writeable');
        }

        if (false === $size = fwrite($this->stream, $string)) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $size;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        return match($this->getMetadata('mode')) {
            'rb', 'w+b', 'a+b',
            'r', 'a+', 'ab+', 'w+', 'wb+', 'x+', 'xb+', 'c+', 'cb+' => true,
            default => false,
        };
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length): string
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        if (false === $this->isReadable()) {
            throw new RuntimeException('Stream is un-readable');
        }

        return fread($this->stream, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        if (null === $this->stream) {
            throw new RuntimeException('Stream is detached');
        }

        if (false === $this->isReadable()) {
            throw new RuntimeException('Stream is unreadable');
        }

        return stream_get_contents($this->stream);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(?string $key = null)
    {
        if (null === $this->stream) {
            // Stream has been detached or closed
            return null;
        }

        $metadata = stream_get_meta_data($this->stream);

        if (null === $key) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
