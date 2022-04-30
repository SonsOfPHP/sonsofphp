<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use Generator;

/**
 * Aggregate
 *
 * Usage:
 *   $aggregate = DummyAggregate::new(AggregateId::fromString('uuid'));
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateInterface
{
    /**
     * Returns the Aggregate ID
     *
     * @return AggregateIdInterface
     */
    public function getAggregateId(): AggregateIdInterface;

    /**
     * Returns the Aggregate Version
     *
     * @return AggregateVersionInterface
     */
    public function getAggregateVersion(): AggregateVersionInterface;

    /**
     * Returns and clears the pending events.
     *
     * Returned events should be persisted in the Event Store and dispatched
     * using the Event Dispatcher
     *
     * @return DomainEventInterface[]
     */
    public function getPendingEvents(): iterable;

    /**
     * Build Aggregate from a collection of Domain Events
     *
     * @param AggregateIdInterface $id
     * @param MessageInterface[] $events
     *
     * @return static
     *
     * @throws EventSourcingException
     */
    public static function buildFromEvents(AggregateIdInterface $id, Generator $events): AggregateInterface;
}
