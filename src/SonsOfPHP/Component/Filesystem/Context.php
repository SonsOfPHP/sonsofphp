<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Filesystem;

use ArrayIterator;
use SonsOfPHP\Component\Filesystem\Exception\FilesystemException;
use Traversable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Context implements ContextInterface
{
    public function __construct(
        private array $context = [],
    ) {}

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_string($offset)) {
            throw new FilesystemException();
        }

        $this->context[$offset] = $value;
    }

    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) {
            throw new FilesystemException();
        }

        return array_key_exists($offset, $this->context);
    }

    public function offsetUnset(mixed $offset): void
    {
        if (!is_string($offset)) {
            throw new FilesystemException();
        }

        unset($this->context[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!is_string($offset)) {
            throw new FilesystemException();
        }

        return $this->context[$offset] ?? null;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->context);
    }
}
