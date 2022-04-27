<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use Generator;

/**
 * Aggregate Trait
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
trait AggregateTrait
{
    private AggregateIdInterface $id;
    private AggregateVersionInterface $version;
    private array $pendingEvents = [];

    /**
     * @param AggregateIdInterface $id
     *
     * @return static
     */
    public static function new(AggregateIdInterface $id)
    {
        $static = new static();
        $static->id = $id;
        $static->version = AggregateVersion::zero();

        return $static;
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregateId(): AggregateIdInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function yieldEvents(): Generator
    {
        foreach ($this->pendingEvents as $event) {
            yield $event;
        }

        $this->pendingEvents = [];
    }

    /**
     * {@inheritdoc}
     */
    public static function buildFromEvents(AggregateIdInterface $id, Generator $events): AggregateInterface
    {
        $aggregate = static::new($id);
    }

    /**
     */
    protected function raiseEvent(MessageInterface $event): void
    {
        // 1. Apply Event
        $this->applyEvent($event);

        // 2. Decorate event with Metadata
        // note on version: because we do this after the event is applied, we don't
        // need to next() the version. Example, 0 - empty state, 1 - first event that
        // modified state
        $event = $event->withMetadata([
            Metadata::AGGREGATE_ID      => $this->getAggregateId()->toString(),
            Metadata::AGGREGATE_Version => $this->getAggregateVersion()->toInt(),
        ]);

        // 3. append to pending events
        $this->pendingEvents[] = $event;
    }

    /**
     */
    protected function applyEvent(MessageInterface $event): void
    {
        $parts  = explode('\\', get_class($event));
        $method = 'apply'.end($parts);

        if (method_exists($this, $method)) {
            $this->$method($event);
        }

        $this->version = $this->version->next();
    }
}
