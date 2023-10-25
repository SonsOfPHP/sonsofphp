<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem\Adapter;

use SonsOfPHP\Component\Filesystem\Exception\UnableToReadFileException;

/**
 * Just keeps files in memory, does not write anything to disk
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class InMemoryAdapter implements AdapterInterface
{
    private array $files = [];

    public function write(string $path, mixed $contents): void
    {
        $this->files[$path] = (string) $contents;
    }

    public function read(string $path): string
    {
        if (!array_key_exists($path, $this->files)) {
            throw new UnableToReadFileException();
        }

        return $this->files[$path];
    }

    public function delete(string $path): void
    {
        unset($this->files[$path]);
    }

    public function copy(string $source, string $destination): void
    {
        $this->files[$destination] = $this->files[$source];
    }

    public function move(string $source, string $destination): void
    {
        $this->copy($source, $destination);
        $this->delete($source);
    }
}
