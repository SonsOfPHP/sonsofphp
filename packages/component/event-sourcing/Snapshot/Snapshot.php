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
    private AggregateIdInterface $id;
    private AggregateVersionInterface $version;
    private $state;

    public function __construct(AggregateIdInterface $id, AggregateVersionInterface $version, $state)
    {
        $this->id      = $id;
        $this->version = $version;
        $this->state   = $state;
    }

    /**
     */
    public function getAggregateId(): AggregateIdInterface
    {
        return $this->id;
    }

    /**
     */
    public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->version;
    }

    /**
     */
    public function getState()
    {
        return $this->state;
    }
}
