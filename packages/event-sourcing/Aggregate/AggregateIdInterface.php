<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate ID
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateIdInterface
{
    /**
     * @return string
     * @see self::toString
     */
    public function __toString(): string;

    /**
     * Returns the ID as a string
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Creates an instance of AggregateIdInterface with the passed in
     * value.
     *
     * Example:
     *   $id = AggregateId::fromString('unique-uuid');
     *
     * @param string $id
     * @return AggregateIdInterface
     * @throws EventSourcingException
     */
    public static function fromString(string $id): AggregateIdInterface;

    /**
     * Compares two Aggregate ID objects and returns true if they are
     * equal
     *
     * @param AggregateIdInterface $that
     * @return bool
     * @throws EventSourcingException
     */
    public function equals(AggregateIdInterface $that): bool;
}
