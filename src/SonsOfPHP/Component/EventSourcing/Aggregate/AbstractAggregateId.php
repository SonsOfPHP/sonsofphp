<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Abstract Aggregate ID.
 *
 * This class is used for when you want to extend it and create your own
 * class like a "UserAggregateId"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAggregateId implements AggregateIdInterface
{
    public function __construct(
        private readonly ?string $id = null,
    ) {
        if (null === $id) {
            throw new EventSourcingException('Argument (#1) $id cannot be null');
        }
    }

    final public function __toString(): string
    {
        return $this->toString();
    }

    final public function toString(): string
    {
        return $this->id;
    }

    final public static function fromString(string $id): AggregateIdInterface
    {
        return new static($id);
    }

    final public function equals(AggregateIdInterface $that): bool
    {
        return $this->toString() === $that->toString();
    }
}
