<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * Abstract Aggregate ID
 *
 * This class is used for when you want to extend it and create your own
 * class like a "UserAggregateId"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAggregateId implements AggregateIdInterface
{
    private string $id;

    /**
     * @param string $id
     */
    final public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @see AggregateIdInterface::toString()
     */
    final public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @see AggregateIdInterface::toString()
     */
    final public function toString(): string
    {
        return $this->id;
    }

    /**
     * @see AggregateIdInterface::fromString()
     */
    final public static function fromString(string $id): AggregateIdInterface
    {
        return new static($id);
    }

    /**
     * @see AggregateIdInterface::equals()
     */
    public function equals(AggregateIdInterface $that): bool
    {
        return ($this->toString() === $that->toString());
    }
}
