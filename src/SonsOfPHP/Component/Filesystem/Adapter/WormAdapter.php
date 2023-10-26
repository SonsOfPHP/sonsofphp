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
final class WormAdapter implements AdapterInterface
{
    public function __construct(
        private AdapterInterface $adapter,
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

    public function copy(string $source, string $destination): void
    {
        if ($this->isFile($destination)) {
            throw new FilesystemException();
        }

        $this->adapter->copy($source, $destination);
    }

    public function move(string $source, string $destination): void
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

    public function isDirectory(string $path): bool
    {
        return $this->adapter->isDirectory($path);
    }
}
