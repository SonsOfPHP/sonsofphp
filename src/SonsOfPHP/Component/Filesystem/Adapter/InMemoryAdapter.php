<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;

/**
 * Just keeps files in memory, does not write anything to disk
 *
 * Usage:
 *   $adapter = new InMemoryAdapter();
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemoryAdapter implements AdapterInterface, CopyAwareInterface, MoveAwareInterface, DirectoryAwareInterface
{
    private array $files = [];

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        $path = $this->normalizePath($path);

        if (is_resource($contents)) {
            $contents = stream_get_contents($contents, null, 0);
        }

        $this->files[$path] = $contents;
    }

    public function get(string $path, ?ContextInterface $context = null): string
    {
        $path = $this->normalizePath($path);

        if (!array_key_exists($path, $this->files)) {
            throw new UnableToReadFileException();
        }

        return $this->files[$path];
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        $path = $this->normalizePath($path);

        unset($this->files[$path]);
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        $source      = $this->normalizePath($source);
        $destination = $this->normalizePath($destination);

        $this->files[$destination] = $this->files[$source];
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        $this->copy($source, $destination);
        $this->remove($source);
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        return $this->isFile($path) || $this->isDirectory($path);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        $path = $this->normalizePath($path);

        return array_key_exists($path, $this->files);
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
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
