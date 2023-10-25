<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

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

    public function write(string $path, mixed $contents): void
    {
        foreach ($this->adapters as $adapter) {
            $adapter->write($contents);
        }
    }

    public function read(string $path): string
    {
        foreach ($this->adapters as $adapter) {
            // if file does not exist, try next adapter
            return $this->adapter->read($path);
        }
    }

    public function delete(string $path): void
    {
        foreach ($this->adapters as $adapter) {
            $this->adapter->delete($path);
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

    public function exists(string $path): bool
    {
        foreach ($this->adapters as $adapter) {
            if ($this->adapter->exists($path)) {
                return true;
            }
        }

        return false;
    }

    public function isFile(string $filename): bool
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
