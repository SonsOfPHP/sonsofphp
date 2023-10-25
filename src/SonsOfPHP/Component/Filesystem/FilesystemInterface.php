<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface FilesystemInterface
{
    /**
     * @throws FilesystemException
     */
    public function write(string $filename, mixed $content): void;
    // or put, putContents

    // could write not do this by default? Maybe option to overwrite if exists?
    //public function append(string $filename, mixed $content): void;

    /**
     * @throws FilesystemException
     */
    public function read(string $filename): string;
    // or get, getContents

    /**
     * @throws FilesystemException
     */
    public function delete(string $filename): void;
    // or remove, rm, rmdir

    /**
     * @throws FilesystemException
     */
    public function copy(string $source, string $destination): void;
    // or cp

    /**
     * @throws FilesystemException
     */
    public function move(string $source, string $destination): void;
    // or mv

    //public function createDirectory(string|iterable $dirs): bool;
    // or mkdir

    //public function isDir(string $filename): bool;

    //public function isFile(string $filename): bool;

    //public function isReadable(string $filename): bool;

    //public function isWritable(string $filename): bool;

    //public function exists(string|iterable $path): bool;
}
