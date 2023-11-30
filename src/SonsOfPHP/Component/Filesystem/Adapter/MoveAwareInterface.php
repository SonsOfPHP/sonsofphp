<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\ContextInterface;
use SonsOfPHP\Component\Filesystem\Exception\FileNotFoundException;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;

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
     * @throws FileNotFoundException When $source does not exist
     * @throws FilesystemException   Generic Failure Exception, might be throw
     *                               if destintation exists
     */
    public function move(string $source, string $destination, ?ContextInterface $context = null): void;
}
