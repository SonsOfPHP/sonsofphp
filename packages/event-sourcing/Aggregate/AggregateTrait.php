<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Generator;

/**
 * Aggregate Trait
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 *
 * @deprecated Use AbstractAggregate
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
     * Returns true if there are pending events that need to be persisted.
     * This will not clear any pending events and just says if there are or are
     * not any pending events
     *
     * @return bool
     */
    public function hasPendingEvents(): bool
    {
        return count($this->pendingEvents) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getPendingEvents(): iterable
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];

        return $events;
    }

    /**
     * {@inheritdoc}
     */
    public static function buildFromEvents(AggregateIdInterface $id, Generator $events): AggregateInterface
    {
        $aggregate = static::new($id);
        foreach ($events as $event) {
            $aggregate->applyEvent($event);
        }

        return $aggregate;
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
            Metadata::AGGREGATE_VERSION => $this->getAggregateVersion()->toInt(),
            Metadata::TIMESTAMP         => (new \DateTimeImmutable())->format(Metadata::DEFAULT_TIMESTAMP_FORMAT),
            Metadata::TIMESTAMP_FORMAT  => Metadata::DEFAULT_TIMESTAMP_FORMAT,
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
