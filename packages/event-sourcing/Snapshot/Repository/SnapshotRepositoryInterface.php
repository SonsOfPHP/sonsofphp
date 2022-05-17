<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Snapshot\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;

/**
 * Snapshot Repository Interface.
 *
 * Similar to the Message Repository, however this is just for snapshots
 *
 * note: There should be different "SnapshotStrategy" that can be used to
 * automatically take a snapshot of the aggregate
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SnapshotRepositoryInterface
{
    public function find(AggregateIdInterface $id): ?SnapshotInterface;

    public function persist(SnapshotInterface $snapshot): void;
}
