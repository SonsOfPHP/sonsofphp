<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

/**
 * If an adapter is able to manage directories, it should implement this
 * interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface DirectoryAwareInterface
{
    /**
     * @throws FilesystemException Generic Failure Exception
     */
    public function isDirectory(string $path, ?ContextInterface $context = null): bool;
}
