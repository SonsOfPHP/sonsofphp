<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

/**
 * Null Adapter does absolutly nothing, it's good for testing and that's pretty
 * much it.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullAdapter implements AdapterInterface
{
    public function write(string $path, mixed $contents): void {}

    public function read(string $path): string
    {
        return '';
    }

    public function delete(string $path): void {}

    public function copy(string $source, string $destination): void {}

    public function move(string $source, string $destination): void {}

    public function exists(string $path): bool
    {
        return false;
    }

    public function isFile(string $filename): bool
    {
        return false;
    }

    public function isDirectory(string $path): bool
    {
        return false;
    }
}
