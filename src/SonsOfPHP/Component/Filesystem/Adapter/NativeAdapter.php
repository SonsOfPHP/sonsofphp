<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * The native adapter will use the underlying filesystem to store files.
 *
 * Usage:
 *   $adapter = new NativeAdapter('/tmp');
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NativeAdapter implements AdapterInterface, CopyAwareInterface, DirectoryAwareInterface, MoveAwareInterface
{
    public function __construct(
        private string $prefix,
    ) {}

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        file_put_contents($this->prefix . $path, $contents);
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        return file_get_contents($this->prefix . $path);
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        unlink($this->prefix . $path);
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        return $this->isFile($path, $context) || $this->isDirectory($path, $context);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        return is_file($filename);
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        copy($this->prefix . $source, $this->prefix . $destination);
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        return is_dir($filename);
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        rename($this->prefix . $source, $this->prefix . $destination);
    }
}
