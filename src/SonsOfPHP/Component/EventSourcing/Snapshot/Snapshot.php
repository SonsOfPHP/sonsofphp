<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Snapshot;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class Snapshot implements SnapshotInterface
{
    public function __construct(private readonly AggregateIdInterface $id, private readonly AggregateVersionInterface $version, private $state) {}

    public function getAggregateId(): AggregateIdInterface
    {
        return $this->id;
    }

    public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->version;
    }

    public function getState()
    {
        return $this->state;
    }
}
