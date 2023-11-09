<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Aggregate.
 *
 * Usage:
 *   $aggregate = new DummyAggregate('unique-id');
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateInterface
{
    /**
     * Returns the Aggregate ID.
     */
    public function getAggregateId(): AggregateIdInterface;

    /**
     * Returns the Aggregate Version.
     */
    public function getAggregateVersion(): AggregateVersionInterface;

    /**
     * Returns and clears the pending events.
     *
     * Returned events should be persisted in the Event Store and dispatched
     * using the Event Dispatcher
     *
     * @return MessageInterface[]
     */
    public function getPendingEvents(): iterable;

    /**
     * Returns pending events, but does not clear them out
     *
     * @return MessageInterface[]
     */
    public function peekPendingEvents(): iterable;

    /**
     * Build Aggregate from a collection of Domain Events.
     *
     * @param iterable $events yields MessageInterface objects
     *
     * @throws EventSourcingException
     */
    public static function buildFromEvents(AggregateIdInterface $id, iterable $events): self;
}
