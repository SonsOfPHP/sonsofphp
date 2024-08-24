<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToCopyFileException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToDeleteFileException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToMoveFileException;
use SonsOfPHP\Component\Filesystem\Exception\UnableToWriteFileException;
use SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\CopyAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\DirectoryAwareInterface;
use SonsOfPHP\Contract\Filesystem\Adapter\MoveAwareInterface;
use SonsOfPHP\Contract\Filesystem\ContextInterface;

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
        private readonly int $defaultPermissions = 0o777,
    ) {
        $this->prefix = rtrim($prefix, '/');
    }

    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void
    {
        if ($this->isFile($path, $context)) {
            throw new FilesystemException(sprintf('File "%s" already exists', $path));
        }

        $this->makeDirectory(dirname($path), $context);

        if (false === file_put_contents($this->prefix . '/' . ltrim($path, '/'), $contents)) {
            throw new UnableToWriteFileException('Unable to write file "' . $path . '"');
        }

        if (false === chmod($this->prefix . '/' . ltrim($path, '/'), $this->defaultPermissions)) {
            throw new FilesystemException('Unable to set permissions on file "' . $path . '"');
        }
    }

    public function get(string $path, ?ContextInterface $context = null): mixed
    {
        if (!$this->isFile($path, $context)) {
            throw new FileNotFoundException('File "' . $path . '" not found');
        }

        return file_get_contents($this->prefix . '/' . ltrim($path, '/'));
    }

    public function remove(string $path, ?ContextInterface $context = null): void
    {
        if (!$this->isFile($path, $context)) {
            throw new FileNotFoundException('File "' . $path . '" not found');
        }

        if (false === unlink($this->prefix . '/' . ltrim($path, '/'))) {
            throw new UnableToDeleteFileException('Unable to remove file "' . $path . '"');
        }
    }

    public function has(string $path, ?ContextInterface $context = null): bool
    {
        return $this->isFile($path, $context) || $this->isDirectory($path, $context);
    }

    public function isFile(string $path, ?ContextInterface $context = null): bool
    {
        return is_file($this->prefix . '/' . ltrim($path, '/'));
    }

    public function copy(string $source, string $destination, ?ContextInterface $context = null): void
    {
        if (!$this->isFile($source)) {
            throw new FilesystemException('Source file "' . $source . '" does not exist');
        }

        if ($this->isFile($destination)) {
            throw new FilesystemException('Destination file "' . $destination . '" already exists');
        }

        if (false === copy($this->prefix . '/' . ltrim($source, '/'), $this->prefix . '/' . ltrim($destination, '/'))) {
            throw new UnableToCopyFileException('Unable to file "' . $source . '" to "' . $destination . '"');
        }
    }

    public function isDirectory(string $path, ?ContextInterface $context = null): bool
    {
        return is_dir($this->prefix . '/' . ltrim($path, '/'));
    }

    public function makeDirectory(string $path, ?ContextInterface $context = null): void
    {
        if (!$this->isDirectory($path) && false === mkdir($this->prefix . '/' . ltrim($path, '/'), $this->defaultPermissions, true)) {
            throw new FilesystemException('Unable to create directory "' . $path . '"');
        }
    }

    public function move(string $source, string $destination, ?ContextInterface $context = null): void
    {
        if (false === rename($this->prefix . '/' . ltrim($source, '/'), $this->prefix . '/' . ltrim($destination, '/'))) {
            throw new UnableToMoveFileException('Unable to move file "' . $source . '" to "' . $destination . '"');
        }
    }
}
