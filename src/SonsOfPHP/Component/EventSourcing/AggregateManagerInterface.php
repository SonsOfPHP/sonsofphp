<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;

/**
 * Primary way to interact with Aggregates
 *
 * Usage:
 *   $aggregate = $manager->find(UserAggregate::class, 'unique-id');
 *   ...
 *   $manager->persist($aggregate);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface AggregateManagerInterface
{
    /**
     * Usage:
     *   $manager->find(UserAggregate::class, 'unique-id');
     */
    public function find(AggregateInterface $class, AggregateIdInterface|string $id): ?AggregateInterface;

    public function persist(AggregateInterface $aggregate): void;
}
