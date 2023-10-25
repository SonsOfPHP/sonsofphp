<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

/**
 * Chain adapter allows you to use multiple adapters together.
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
}
