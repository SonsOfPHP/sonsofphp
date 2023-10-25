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
     * @throws FilesystemException
     */
    public function write(string $filename, mixed $contents): void;

    /**
     * @throws FilesystemException
     */
    public function read(string $filename): string;
}
