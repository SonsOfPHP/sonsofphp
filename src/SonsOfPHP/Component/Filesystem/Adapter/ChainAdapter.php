<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * Chain adapter allows you to use multiple adapters together.
 *
 * Usage:
 *   $adapter = new ChainAdapter([new InMemoryAdapter(), new NativeAdapter('/tmp')]);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ChainAdapter implements AdapterInterface, CopyAwareInterface, DirectoryAwareInterface, MoveAwareInterface
{
    public function __construct(
        private iterable $adapters,
    ) {}

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        foreach ($this->adapters as $adapter) {
            $adapter->add($path, $contents, $context);
        }
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->has($path, $context)) {
                return $this->adapter->get($path, $context);
            }
        }

        throw new FileNotFoundException();
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        foreach ($this->adapters as $adapter) {
            $this->adapter->remove($path, $context);
        }
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->has($path, $context)) {
                return true;
            }
        }

        return false;
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->isFile($path, $context)) {
                return true;
            }
        }

        return false;
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof CopyAwareInterface) {
                $adapter->copy($source, $destination, $context);
                continue;
            }

            $adapter->add($destination, $adapter->get($source, $context), $context);
        }
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof DirectoryAwareInterface && $adapter->isDirectory($path, $context)) {
                return true;
            }
        }

        return false;
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter instanceof MoveAwareInterface) {
                $adapter->move($source, $destination, $context);
                continue;
            }

            $adapter->add($destination, $adapter->get($source, $context), $context);
            $adapter->remove($source, $context);
        }
    }
}
