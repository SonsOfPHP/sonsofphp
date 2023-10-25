<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

/**
 * The native adapter will use the underlying filesystem to store files.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class NativeAdapter implements AdapterInterface
{
    public function __construct(
        private string $prefix = '/tmp',
    ) {}

    public function write(string $path, mixed $contents): void
    {
        file_put_contents($this->prefix . $path, $contents);
    }

    public function read(string $path): string
    {
        return file_get_contents($this->prefix . $path);
    }
}
