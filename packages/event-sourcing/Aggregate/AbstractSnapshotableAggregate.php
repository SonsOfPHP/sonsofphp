<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use Generator;
use SonsOfPHP\Component\EventSourcing\Snapshot\Snapshot;
use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractSnapshotableAggregate extends AbstractAggregate
{
    /**
     * Return the current state of the aggregate. This is
     * used as the snapshot state.
     *
     * @return mixed
     */
    abstract protected function createSnapshotState();

    /**
     * @see SnapshotableAggregateInterface::buildFromSnapshot
     */
    abstract public static function buildFromSnapshot(SnapshotInterface $snapshot): SnapshotableAggregateInterface;

    /**
     * @see SnapshotableAggregateInterface::createSnapshot
     */
    public function createSnapshot(): SnapshotInterface
    {
        return new Snapshot($this->getAggregateId(), $this->getAggregateVersion(), $this->createSnapshotState());
    }

    /**
     * @see SnapshotableAggregateInterface::buildFromSnapshotAndEvents
     */
    public static function buildFromSnapshotAndEvents(SnapshotInterface $snapshot, Generator $messages): SnapshotableAggregateInterface
    {
        $aggregate = static::buildFromSnapshot($snapshot);
        foreach ($messages as $msg) {
            $aggregate->applyEvent($msg); // @phpstan-ignore-line
        }

        return $aggregate;
    }
}
