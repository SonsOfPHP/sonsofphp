<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Contract\Cqrs\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractMessage implements MessageInterface, \JsonSerializable, \Serializable
{
    private array $payload = [];

    public function with(string|array $key, mixed $value): static
    {
        if (is_array($key)) {
            $that = clone $this;
            $that->payload = $key;

            return $that;
        }

        if (array_key_exists($key, $this->payload) && $value === $this->payload[$key]) {
            return $this;
        }

        $that = clone $this;
        $that->payload[$key] = $value;

        return $that;
    }

    public function get(?string $key = null): mixed
    {
        if (null === $key) {
            return $this->payload;
        }

        if (!array_key_exists($key, $this->payload)) {
            throw new \Exception(sprintf('The key "%s" does not exist.', $key));
        }
    }

    public function serialize(): ?string
    {
        if (false === $json = json_encode($this)) {
            throw new \Exception('Unable to serialize object');
        }

        return $json;
    }

    public function unserialize(string $data): void
    {
        $this->payload = json_decode($data, true);
    }

    public function jsonSerialize(): array
    {
        return $this->payload;
    }
}
