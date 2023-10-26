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
final class ChainAdapter implements AdapterInterface
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

    public function copy(string $source, string $destination): void
    {
        foreach ($this->adapters as $adapter) {
            $this->adapter->copy($source, $destination);
        }
    }

    public function move(string $source, string $destination): void
    {
        foreach ($this->adapters as $adapter) {
            $this->adapter->move($source, $destination);
        }
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->has($path)) {
                return true;
            }
        }

        return false;
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->isFile($path)) {
                return true;
            }
        }

        return false;
    }

    public function isDirectory(string $path): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->isDirectory($path)) {
                return true;
            }
        }

        return false;
    }
}
