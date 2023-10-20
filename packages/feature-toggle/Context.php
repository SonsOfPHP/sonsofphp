<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Context implements ContextInterface
{
    public function __construct(private array $data = []) {}

    public function offsetSet($offset, $value): void
    {
        if (null === $offset) {
            throw new \Exception('Requires a key');
        }

        $this->data[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->data);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    public function get(string $key): mixed
    {
        return $this->offsetGet($key);
    }

    public function set(string $key, $value): ContextInterface
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }
}
