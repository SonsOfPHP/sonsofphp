<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Snapshot\SnapshotInterface;
use Generator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 *
 * @todo Make AbstractSnapshotableAggregate and deprecate this trait
 */
trait SnapshotableAggregateTrait
{
    use AggregateTrait;

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
            $aggregate->applyEvent($msg);
        }

        return $aggregate;
    }
}
