<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

use Generator;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAggregate implements AggregateInterface
{
    private AggregateIdInterface $id;
    private AggregateVersionInterface $version;
    private array $pendingEvents = [];

    /**
     * @param AggregateIdInterface|string $id
     */
    final public function __construct($id)
    {
        if (!$id instanceof AggregateIdInterface && !\is_string($id)) {
            throw new EventSourcingException(sprintf('Argument #1 ($id) must be of of type string or "%s". Type "%s" passed in.', AggregateIdInterface::class, \gettype($id)));
        }

        if (!$id instanceof AggregateIdInterface) {
            $id = new AggregateId($id);
        }

        $this->id = $id;
        $this->version = new AggregateVersion();
    }

    /**
     * @param AggregateIdInterface|string $id
     *
     * @return static
     */
    final public static function new($id)
    {
        $static = new static($id);

        return $static;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateId(): AggregateIdInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->version;
    }

    /**
     * Returns true if there are pending events that need to be persisted.
     * This will not clear any pending events and just says if there are or are
     * not any pending events.
     */
    final public function hasPendingEvents(): bool
    {
        return \count($this->pendingEvents) > 0;
    }

    /**
     * {@inheritdoc}
     */
    final public function getPendingEvents(): iterable
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];

        return $events;
    }

    /**
     * {@inheritdoc}
     */
    final public static function buildFromEvents(AggregateIdInterface $id, Generator $events): AggregateInterface
    {
        $aggregate = new static($id);
        foreach ($events as $event) {
            $aggregate->applyEvent($event);
        }

        return $aggregate;
    }

    /**
     * Raise New Event.
     *
     * Raised Events will be apply to the aggregate and when the aggregate is persisted
     * using the the Aggregate Repository, the event is stored
     */
    final protected function raiseEvent(MessageInterface $event): void
    {
        // Prepopulate with some metadata
        $event = $event->withMetadata([
            Metadata::AGGREGATE_ID => $this->getAggregateId()->toString(),
            Metadata::TIMESTAMP => (new \DateTimeImmutable())->format(Metadata::DEFAULT_TIMESTAMP_FORMAT),
            Metadata::TIMESTAMP_FORMAT => Metadata::DEFAULT_TIMESTAMP_FORMAT,
        ]);

        // 1. Apply Event
        $this->applyEvent($event);

        // 2. Decorate event with Metadata
        // note on version: because we do this after the event is applied, we don't
        // need to next() the version. Example, 0 - empty state, 1 - first event that
        // modified state
        $event = $event->withMetadata([
            Metadata::AGGREGATE_VERSION => $this->getAggregateVersion()->toInt(),
        ]);

        // 3. append to pending events
        $this->pendingEvents[] = $event;
    }

    /**
     * Apply Event.
     *
     * Events are applied when they are raised and when rebuilding an aggregate
     * from events stored in the database.
     */
    final protected function applyEvent(MessageInterface $event): void
    {
        $parts = explode('\\', $event::class);
        $method = 'apply'.end($parts);

        if (method_exists($this, $method)) {
            $this->$method($event); // @phpstan-ignore-line
        }

        $this->version = $this->version->next();
    }
}
