<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Logger;

use SonsOfPHP\Contract\Logger\ContextInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Context implements ContextInterface
{
    public function __construct(
        private array $context = [],
    ) {}

    public function all(): array
    {
        return $this->context;
    }

    public function offsetExists(mixed $offset): bool
    {
        if (!is_string($offset)) {
            throw new \InvalidArgumentException('Only strings are supported as keys');
        }

        return array_key_exists($offset, $this->context);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!is_string($offset)) {
            throw new \InvalidArgumentException('Only strings are supported as keys');
        }

        return $this->context[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_string($offset)) {
            throw new \InvalidArgumentException('Only strings are supported as keys');
        }

        if (is_object($value) && !$value instanceof \Stringable) {
            throw new \InvalidArgumentException('Only Stringable Objects are supported');
        }

        $this->context[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        if (!is_string($offset)) {
            throw new \InvalidArgumentException('Only strings are supported as keys');
        }

        if ($this->offsetExists($offset)) {
            unset($this->context[$offset]);
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->context);
    }
}
