<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

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
    }

    public function read(string $path): string
    {
        return $this->adapter->read($path);
    }

    public function delete(string $path): void
    {
        // @todo throw exception
    }

    public function copy(string $source, string $destination): void
    {
        // @todo throw exception if destination exists
    }

    public function move(string $source, string $destination): void
    {
        // @todo throw exception
    }
}
