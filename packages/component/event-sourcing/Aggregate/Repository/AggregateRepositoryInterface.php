<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;

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
     * 1. Enrich Event Message
     * 2. Persist Event
     * 3. Dispatch Event (Event Dispatcher, Event Bus, etc.)
     */
    public function persist(AggregateInterface $aggregate): void;
}
