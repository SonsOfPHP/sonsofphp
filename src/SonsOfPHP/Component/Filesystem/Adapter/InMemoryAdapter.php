<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;

/**
 * Just keeps files in memory, does not write anything to disk
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemoryAdapter implements AdapterInterface
{
    private array $files = [];

    public function write(string $path, mixed $contents): void
    {
        $path = $this->normalizePath($path);

        if (is_resource($contents)) {
            $contents = stream_get_contents($contents, null, 0);
        }

        $this->files[$path] = $contents;
    }

    public function read(string $path): string
    {
        $path = $this->normalizePath($path);

        if (!array_key_exists($path, $this->files)) {
            throw new UnableToReadFileException();
        }

        return $this->files[$path];
    }

    public function delete(string $path): void
    {
        $path = $this->normalizePath($path);

        unset($this->files[$path]);
    }

    public function copy(string $source, string $destination): void
    {
        $source      = $this->normalizePath($source);
        $destination = $this->normalizePath($destination);

        $this->files[$destination] = $this->files[$source];
    }

    public function move(string $source, string $destination): void
    {
        $this->copy($source, $destination);
        $this->delete($source);
    }

    public function exists(string $path): bool
    {
        return $this->isFile($path) || $this->isDirectory($path);
    }

    public function isFile(string $path): bool
    {
        $path = $this->normalizePath($path);

        return array_key_exists($path, $this->files);
    }

    public function isDirectory(string $path): bool
    {
        $path = $this->normalizePath($path);

        foreach ($this->files as $key => $contents) {
            $parts = explode('/', $key);
            array_pop($parts);

            if (implode('/', $parts) === $path) {
                return true;
            }
        }

        return false;
    }

    private function normalizePath(string $path): string
    {
        return ltrim($path, '/');
    }
}
