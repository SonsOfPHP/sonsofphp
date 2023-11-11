<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * {@inheritdoc}
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        private ?StreamInterface $stream,
        private ?int $size = null,
        private int $error = \UPLOAD_ERR_OK,
        private ?string $clientFilename = null,
        private ?string $clientMediaType = null,
    ) {
        if (null === $stream || !$stream->isReadable()) {
            throw new \InvalidArgumentException('Stream is invalid');
        }

        if (null === UploadedFileError::tryFrom($error)) {
            throw new \InvalidArgumentException(sprintf('The value "%s" for $error is invalid.', $error));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStream(): StreamInterface
    {
        if (null === $this->stream) {
            throw new \RuntimeException('File has already been moved');
        }

        return $this->stream;
    }

    /**
     * {@inheritdoc}
     */
    public function moveTo(string $targetPath): void
    {
        // @todo throw new \InvalidArgumentExcpetion if targetPath is invalid

        if (null === $this->stream) {
            throw new \RuntimeException();
        }

        if (false === move_uploaded_file($this->stream->getMetadata('uri'), $targetPath)) {
            throw new \RuntimeException('File could not be moved');
        }

        $this->stream = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        if (null !== $this->size) {
            return $this->size;
        }

        if (null !== $this->stream) {
            return $this->stream->getSize();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
