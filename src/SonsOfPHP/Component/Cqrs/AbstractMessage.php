<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Contract\Cqrs\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractMessage implements MessageInterface, \JsonSerializable, \Serializable, \IteratorAggregate
{
    private array $payload = [];

    /**
     * {@inheritdoc}
     */
    public function with(string|array $key, mixed $value = null): static
    {
        if (is_object($value) && !$value instanceof \Stringable) {
            throw new \InvalidArgumentException('$value is invalid');
        }

        if (is_array($key) && null !== $value) {
            throw new \InvalidArgumentException('$key is invalid, you cannot pass in $value when $key is an array');
        }

        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        if (is_array($key)) {
            $that = clone $this;
            foreach ($key as $k => $v) {
                if (is_object($v) && !$v instanceof \Stringable) {
                    throw new \InvalidArgumentException('The array contains invalid values');
                }

                if ($v instanceof \Stringable) {
                    $v = (string) $v;
                }

                $that->payload[$k] = $v;
            }

            return $that;
        }

        if (array_key_exists($key, $this->payload) && $value === $this->payload[$key]) {
            return $this;
        }

        $that = clone $this;
        $that->payload[$key] = $value;

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    public function get(?string $key = null): mixed
    {
        if (null === $key) {
            return $this->payload;
        }

        if (!array_key_exists($key, $this->payload)) {
            throw new \Exception(sprintf('The key "%s" does not exist.', $key));
        }

        return $this->payload[$key];
    }

    public function serialize(): ?string
    {
        if (false === $json = json_encode($this)) {
            throw new \RuntimeException('Unable to serialize object');
        }

        return $json;
    }

    public function unserialize(string $data): void
    {
        $this->payload = json_decode($data, true);
    }

    public function __serialize(): array
    {
        return $this->payload;
    }

    public function __unserialize(array $data): void
    {
        $this->payload = $data;
    }

    public function jsonSerialize(): array
    {
        return $this->payload;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->payload);
    }
}
