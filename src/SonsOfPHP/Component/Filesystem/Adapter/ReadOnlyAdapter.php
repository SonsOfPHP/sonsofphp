<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * The Read Only adapter will only allow you to read existing files, if you try
 * to create anything, an exception will be thrown
 *
 * Usage:
 *   $adapter = new ReadOnlyAdapter(new NativeAdapter('/tmp'));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReadOnlyAdapter implements AdapterInterface
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
}
