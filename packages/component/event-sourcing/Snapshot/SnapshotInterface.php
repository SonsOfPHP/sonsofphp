<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Snapshot;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;

/**
 * Snapshot Interface
 *
 * Snapshots are created by Aggregates and stored using a SnapshotRepository
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface SnapshotInterface
{
    /**
     */
    public function getAggregateId(): AggregateIdInterface;

    /**
     */
    public function getAggregateVersion(): AggregateVersionInterface;

    /**
     * @return mixed
     */
    public function getState();
}
