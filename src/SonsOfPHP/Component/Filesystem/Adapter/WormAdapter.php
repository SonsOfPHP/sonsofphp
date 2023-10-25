<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * WORM = Write Once, Read Many. Using this adapter allows you to create new
 * files if they do not already exist, but you will be unable to update or
 * remove that file.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class WormAdapter implements AdapterInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function write(string $path, mixed $contents): void
    {
        // @todo throw exception if file already exists

        $this->adapter->write($path, $contents);
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
        // @todo throw exception if destination exists

        $this->adapter->copy($source, $destination);
    }

    public function move(string $source, string $destination): void
    {
        throw new FilesystemException();
    }
}
