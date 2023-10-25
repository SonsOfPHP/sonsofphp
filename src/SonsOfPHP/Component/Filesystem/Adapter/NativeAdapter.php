<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

/**
 * The native adapter will use the underlying filesystem to store files.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NativeAdapter implements AdapterInterface
{
    public function __construct(
        private string $prefix,
    ) {}

    public function write(string $path, mixed $contents): void
    {
        file_put_contents($this->prefix . $path, $contents);
    }

    public function read(string $path): string
    {
        return file_get_contents($this->prefix . $path);
    }

    public function delete(string $path): void
    {
        unlink($this->prefix . $path);
    }

    public function copy(string $source, string $destination): void
    {
        copy($this->prefix . $source, $this->prefix . $destination);
    }

    public function move(string $source, string $destination): void
    {
        rename($this->prefix . $source, $this->prefix . $destination);
    }

    public function exists(string $path): bool
    {
        return $this->isFile($path) || $this->isDirectory($path);
    }

    public function isFile(string $filename): bool
    {
        return is_file($filename);
    }

    public function isDirectory(string $path): bool
    {
        return is_dir($filename);
    }
}
