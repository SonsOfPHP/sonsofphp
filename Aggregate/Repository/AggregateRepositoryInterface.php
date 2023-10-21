<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Aggregate Repository Interface.
 *
 * This will use a Message Repository to store Messages and pull Messages from
 * storage. The messages pulled from storage are used to build the aggregate
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateRepositoryInterface
{
    /**
     * Finds and builds the aggregate from the events in storage. If no
     * Aggregate events found in storage, this will return null.
     *
     * @thorws EventSourcingException
     */
    public function find(AggregateIdInterface|string $id): ?AggregateInterface;

    /**
     * When an aggregate is persisted, it will dispatch pending events.
     *
     * 1. Enrich Event Message
     * 2. Persist Event
     * 3. Dispatch Event (Event Dispatcher, Event Bus, etc.)
     *
     * @throws EventSourcingException
     */
    public function persist(AggregateInterface $aggregate): void;
}
