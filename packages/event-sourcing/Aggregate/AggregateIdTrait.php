<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * Aggregate ID Trait
 *
 * Using this trait, you can create a "UserId" class for type hinting
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
trait AggregateIdTrait
{
    private string $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @see AggregateIdInterface::toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @see AggregateIdInterface::toString()
     */
    public function toString(): string
    {
        return (string) $this->id;
    }

    /**
     * @see AggregateIdInterface::fromString()
     */
    public static function fromString(string $id): AggregateIdInterface
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
