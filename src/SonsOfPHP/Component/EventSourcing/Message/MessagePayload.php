<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * The Event Message Payload is stored in here.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessagePayload implements IteratorAggregate, Countable
{
    public function __construct(
        private array $payload = [],
    ) {}

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->payload);
    }

    public function count(): int
    {
        return \count($this->payload);
    }

    public function all(): array
    {
        return $this->payload;
    }

    public function with(string $key, $value): self
    {
        $that                = clone $this;
        $that->payload[$key] = $value;

        return $that;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->payload);
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->payload[$key];
        }
    }
}
