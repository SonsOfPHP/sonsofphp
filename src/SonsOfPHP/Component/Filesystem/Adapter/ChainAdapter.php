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
        // @todo
    }

    public function read(string $path): string
    {
        // @todo
        return '';
    }

    public function delete(string $path): void
    {
        // @todo
    }

    public function copy(string $source, string $destination): void
    {
        // @todo
    }

    public function move(string $source, string $destination): void
    {
        // @todo
    }
}
