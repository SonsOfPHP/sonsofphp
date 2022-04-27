<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * Snapshot Repository Interface
 *
 * Similar to the Message Repository, however this is just for snapshots
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SnapshotRepositoryInterface
{
    /**
     */
    public function find(AggregateIdInterface $id): ?SnapshotInterface;

    /**
     */
    public function persist(SnapshotInterface $snapshot): void;
}
