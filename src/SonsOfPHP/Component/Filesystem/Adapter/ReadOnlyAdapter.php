<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ReadOnlyAdapter implements AdapterInterface
{
    public function write(string $path, mixed $contents): void
    {
        // @todo throw exception
    }

    public function read(string $path): string
    {
        return '';
    }

    public function delete(string $path): void
    {
        // @todo throw exception
    }

    public function copy(string $source, string $destination): void
    {
        // @todo throw exception
    }

    public function move(string $source, string $destination): void
    {
        // @todo throw exception
    }
}
