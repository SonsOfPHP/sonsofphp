<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Snapshot\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemorySnapshotRepository implements SnapshotRepositoryInterface
{
    private array $storage = [];

    /**
     * {@inheritdoc}
     */
    public function find(AggregateIdInterface $id): ?SnapshotInterface
    {
        return $this->storage[$id->toString()] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(SnapshotInterface $snapshot): void
    {
        $this->storage[$snapshot->getAggregateId()->toString()] = $snapshot;
    }
}
