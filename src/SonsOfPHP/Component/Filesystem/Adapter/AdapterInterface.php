<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AdapterInterface
{
    /**
     * @param string|resource $contents
     *   Can either be a string or a resource
     *
     * @throws FilesystemException
     */
    public function write(string $filename, mixed $contents): void;

    /**
     * @throws FilesystemException
     */
    public function read(string $filename): string;

    /**
     * Deletes files and directories
     *
     * @throws FilesystemException
     */
    public function delete(string $path): void;

    /**
     * @todo Should this be a SupportsCopyInterface?
     *
     * @throws FilesystemException
     */
    public function copy(string $source, string $destination): void;

    /**
     * @todo Should this be a SupportsMoveInterface?
     *
     * @throws FilesystemException
     */
    public function move(string $source, string $destination): void;

    public function exists(string $path): bool;

    public function isFile(string $filename): bool;

    public function isDirectory(string $path): bool;
}
