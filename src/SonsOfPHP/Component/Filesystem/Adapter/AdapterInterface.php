<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * Base Adapter Interface that all adapters implement.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AdapterInterface
{
    /**
     * Adds a file to the filesystem.
     *
     * @param string|resource $contents
     *   Can either be a string or a resource. If not one of these types, the
     *   adapter should throw an exception
     *
     * @throws FilesystemException When $contents is invalid argument
     * @throws FilesystemException Generic Failure Exception
     */
    public function add(string $path, mixed $contents, ?ContextInterface $context = null): void;

    /**
     * Returns the content of a given file found at $path
     *
     * @throws FileNotFoundException When $path does not exist
     * @throws FilesystemException   Generic Failure Exception
     *
     * @return string|resouce
     *   This should return either a string or resouce
     */
    public function get(string $path, ?ContextInterface $context = null): mixed;

    /**
     * Deletes files and directories
     *
     * @throws FileNotFouneException When $path does not exist
     * @throws FilesystemException   Generic Failure Exception
     */
    public function remove(string $path, ?ContextInterface $context = null): void;

    /**
     * Checks to see if a file or directory exists
     *
     * @throws FilesystemException Generic Failure Exception
     */
    public function has(string $path, ?ContextInterface $context = null): bool;

    /**
     * @throws FilesystemException Generic Failure Exception
     */
    public function isFile(string $path, ?ContextInterface $context = null): bool;
}
