<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * Aggregate Repository Interface
 *
 * This will use a Message Repository to store Messages and pull Messages from
 * storage. The messages pulled from storage are used to build the aggregate
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateRepositoryInterface
{
    /**
     */
    public function find(AggregateIdInterface $id): AggregateInterface;

    /**
     * When an aggregate is persisted, it will dispatch pending events.
     *
     * 1. Persist Event
     * 2. Dispatch Event (Event Dispatcher, Event Bus, etc.)
     */
    public function persist(AggregateInterface $aggregate): void;
}
