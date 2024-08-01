<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * WORM = Write Once, Read Many. Using this adapter allows you to create new
 * files if they do not already exist, but you will be unable to update or
 * remove that file.
 *
 * Usage:
 *   $adapter = new WormAdapter(new NativeAdapter('/tmp'));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class WormAdapter implements AdapterInterface, CopyAwareInterface, DirectoryAwareInterface, MoveAwareInterface
{
    public function __construct(
        private readonly AdapterInterface $adapter,
    ) {}

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        if ($this->isFile($path)) {
            throw new FilesystemException();
        }

        $this->adapter->add($path, $contents);
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        return $this->adapter->get($path);
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
        if ($this->isFile($destination)) {
            throw new FilesystemException();
        }

        if ($this->adapter instanceof CopyAwareInterface) {
            $this->adapter->copy($source, $destination, $context);
            return;
        }

        $this->adapter->add($destination, $this->adapter->get($source, $context), $context);
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        if ($this->adapter instanceof DirectoryAwareInterface) {
            return $this->adapter->isDirectory($path, $context);
        }

        return !$this->isFile($path, $context);
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        throw new FilesystemException();
    }
}
