<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem;

use SonsOfPHP\Component\Filesystem\Adapter\AdapterInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Filesystem implements FilesystemInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function write(string $filename, mixed $contents): void
    {
        $this->adapter->write($filename, $contents);
    }

    public function read(string $filename): string
    {
        return $this->adapter->read($filename);
    }

    public function delete(string $filename): void
    {
        $this->adapter->delete($filename);
    }

    public function copy(string $source, string $destination): void
    {
        $this->adapter->copy($source, $destination);
    }

    public function move(string $source, string $destination): void
    {
        $this->adapter->move($source, $destination);
    }
}
