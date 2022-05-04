<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;
use Generator;

/**
 * Snapshotable Aggregate Interface
 *
 * Aggregates that implement this can create snapshots and store those
 * snapshots
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SnapshotableAggregateInterface extends AggregateInterface
{
    /**
     */
    public function createSnapshot(): SnapshotInterface;

    /**
     */
    public static function buildFromSnapshot(SnapshotInterface $snapshot): SnapshotableAggregateInterface;

    /**
     */
    public static function buildFromSnapshotAndEvents(SnapshotInterface $snapshot, Generator $events): SnapshotableAggregateInterface;
}
