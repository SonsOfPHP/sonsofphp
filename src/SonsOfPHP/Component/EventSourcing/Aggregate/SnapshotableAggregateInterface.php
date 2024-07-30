<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use Generator;
use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;

/**
 * Snapshotable Aggregate Interface.
 *
 * Aggregates that implement this can create snapshots and store those
 * snapshots
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SnapshotableAggregateInterface extends AggregateInterface
{
    public function createSnapshot(): SnapshotInterface;

    public static function buildFromSnapshot(SnapshotInterface $snapshot): self;

    public static function buildFromSnapshotAndEvents(SnapshotInterface $snapshot, Generator $events): self;
}
