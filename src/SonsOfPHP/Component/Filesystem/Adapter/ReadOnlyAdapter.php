<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReadOnlyAdapter implements AdapterInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function write(string $path, mixed $contents): void
    {
        throw new FilesystemException();
    }

    public function read(string $path): string
    {
        return $this->adapter->read($path);
    }

    public function delete(string $path): void
    {
        throw new FilesystemException();
    }

    public function copy(string $source, string $destination): void
    {
        throw new FilesystemException();
    }

    public function move(string $source, string $destination): void
    {
        throw new FilesystemException();
    }
}
