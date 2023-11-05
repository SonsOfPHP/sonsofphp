<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate ID.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateIdInterface extends \Stringable
{
    /**
     * Create a new AggregateID.
     *
     * @param string $id The value of the ID to use, some Aggregate ID classes can auto-generate an ID
     *
     * @throws EventSourcingException If the class does not support auto-generating IDs
     */
    public function __construct(string $id = null);

    /**
     * @see self::toString
     */
    public function __toString(): string;

    /**
     * Returns the ID as a string.
     */
    public function toString(): string;

    /**
     * Compares two Aggregate ID objects and returns true if they are
     * equal.
     *
     * @throws EventSourcingException
     */
    public function equals(AggregateIdInterface $that): bool;
}
