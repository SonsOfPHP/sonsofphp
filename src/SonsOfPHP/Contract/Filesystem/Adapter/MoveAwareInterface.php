<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Filesystem\Adapter;

use SonsOfPHP\Contract\Filesystem\ContextInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FileNotFoundExceptionInterface;
use SonsOfPHP\Contract\Filesystem\Exception\FilesystemExceptionInterface;

/**
 * If an adapter supports the ability to move a file from one place to another
 * they should implement this interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MoveAwareInterface
{
    /**
     * Moves file from $source to $destination
     *
     * @throws FileNotFoundExceptionInterface When $source does not exist
     * @throws FilesystemExceptionInterface   Generic Failure Exception, might be throw
     *                                        if destintation exists
     */
    public function move(string $source, string $destination, ?ContextInterface $context = null): void;
}
