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
    //public function copy(string $source, string $destination, $context): bool;

    //public function move(string $source, string $destination, $context): bool;

    /**
     * @throws FilesystemException
     */
    //public function delete(string $filename): bool;

    /**
     * @throws FilesystemException
     */
    public function write(string $filename, mixed $content): void;

    /**
     * @throws FilesystemException
     */
    public function read(string $filename): string;

    //public function mkdir(string $dir): bool;

    //public function isDir(string $filename): bool;

    //public function isFile(string $filename): bool;

    //public function isReadable(string $filename): bool;

    //public function isWritable(string $filename): bool;
}
