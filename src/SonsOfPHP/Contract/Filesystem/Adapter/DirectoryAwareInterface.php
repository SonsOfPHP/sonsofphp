<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Filesystem\Adapter;

use SonsOfPHP\Contract\Filesystem\ContextInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;

/**
 * If an adapter is able to manage directories, it should implement this
 * interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface DirectoryAwareInterface
{
    /**
     * @throws FilesystemExceptionInterface Generic Failure Exception
     */
    public function isDirectory(string $path, ?ContextInterface $context = null): bool;
}
