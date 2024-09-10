<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;

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
            throw new UnableToReadFileException(sprintf('No file was found at "%s"', $path));
        }

        return $this->files[$path];
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        $path = $this->normalizePath($path);

        foreach (array_keys($this->files) as $key) {
            if ($path === $key || str_starts_with($key, $path)) {
                unset($this->files[$key]);
            }
        }
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
        if ($this->isFile($path)) {
            return true;
        }
        return $this->isDirectory($path);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        $path = $this->normalizePath($path);

        return array_key_exists($path, $this->files);
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        $path = $this->normalizePath($path);

        foreach (array_keys($this->files) as $key) {
            $parts = explode('/', $key);
            array_pop($parts);

            if (str_starts_with($key, $path)) {
                return true;
            }
        }

        return false;
    }

    public function mimeType(string $path, ?ContextInterface $context = null): string
    {
        throw new FilesystemException('Not Supported');
    }

    public function makeDirectory(string $path, ?ContextInterface $context = null): void
    {
        $path = $this->normalizePath($path);

        $this->files[$path] = null;
    }

    public function removeDirectory(string $path, ?ContextInterface $context = null): void
    {
        $path = $this->normalizePath($path);

        foreach (array_keys($this->files) as $key) {
            if (str_starts_with($key, $path)) {
                unset($this->files[$key]);
                return;
            }
        }
    }

    private function normalizePath(string $path): string
    {
        return ltrim($path, '/');
    }
}
