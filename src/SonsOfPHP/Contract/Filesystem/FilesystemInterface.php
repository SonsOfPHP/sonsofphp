<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Filesystem;

use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FilesystemInterface
{
    /**
     * @param string|resource $content
     *
     * @throws FilesystemExceptionInterface
     */
    public function write(string $path, mixed $content, ?ContextInterface $context = null): void;
    // or put, putContents

    // could write not do this by default? Maybe option to overwrite if exists?
    //public function append(string $filename, mixed $content): void;

    /**
     * @throws FilesystemExceptionInterface
     */
    public function read(string $path, ?ContextInterface $context = null): string;
    // or get, getContents

    /**
     * @throws FilesystemExceptionInterface
     */
    public function delete(string $path, ?ContextInterface $context = null): void;
    // or remove, rm, rmdir

    /**
     * Checks to see if a file or directory exists
     *
     * @throws FilesystemExceptionInterface Generic Failure Exception
     */
    public function exists(string $path, ?ContextInterface $context = null): bool;

    //public function isFile(string $path, ?ContextInterface $context = null): bool;

    /**
     * @throws FilesystemExceptionInterface
     */
    public function copy(string $source, string $destination, ?ContextInterface $context = null): void;
    // or cp

    /**
     * @throws FilesystemExceptionInterface
     */
    public function move(string $source, string $destination, ?ContextInterface $context = null): void;
    // or mv

    //public function createDirectory(string|iterable $dirs): bool;
    // or mkdir

    //public function isDir(string $filename): bool;

    //public function isReadable(string $filename): bool;

    //public function isWritable(string $filename): bool;
}
