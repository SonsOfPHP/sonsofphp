<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;

/**
 * The Read Only adapter will only allow you to read existing files, if you try
 * to create anything, an exception will be thrown
 *
 * Usage:
 *   $adapter = new ReadOnlyAdapter(new NativeAdapter('/tmp'));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class ReadOnlyAdapter implements AdapterInterface, CopyAwareInterface, DirectoryAwareInterface, MoveAwareInterface
{
    public function __construct(
        private AdapterInterface $adapter,
    ) {}

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        return $this->adapter->get($path, $context);
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        return $this->adapter->has($path, $context);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        return $this->adapter->isFile($path, $context);
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        if ($this->adapter instanceof DirectoryAwareInterface) {
            return $this->adapter->isDirectory($path, $context);
        }

        return false;
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }

    public function mimeType(string $path, ?ContextInterface $context = null): string
    {
        return $this->adapter->mimeType($path, $context);
    }

    public function makeDirectory(string $path, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }

    public function removeDirectory(string $path, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }
}
