<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;

/**
 * The Event Message Metadata is stored in here
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessageMetadata implements \IteratorAggregate, \Countable
{
    public function __construct(
        private array $metadata = [],
    ) {
    }

    /**
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->metadata);
    }

    /**
     */
    public function count(): int
    {
        return count($this->metadata);
    }

    /**
     */
    public function with(string $key, $value)
    {
        $that = clone $this;
        $that->metadata[$key] = $value;

        return $that;
    }

    /**
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->metadata);
    }

    /**
     * Returns the raw data
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->metadata[$key];
        }
    }

    /**
     */
    public function getEventId(): string
    {
        if (false === $this->has(Metadata::EVENT_ID)) {
            throw new EventSourcingException('Event ID is required.');
        }

        return $this->get(Metadata::EVENT_ID);
    }

    /**
     */
    public function getEventType(): string
    {
        if (false === $this->has(Metadata::EVENT_TYPE)) {
            throw new EventSourcingException('Event Type is required.');
        }

        return $this->get(Metadata::EVENT_TYPE);
    }

    /**
     */
    public function getTimestamp(): \DateTimeImmutable
    {
        if (false === $this->has(Metadata::TIMESTAMP)) {
            throw new EventSourcingException('Timestamp is required.');
        }

        return new \DateTimeImmutable($this->get(Metadata::TIMESTAMP));
    }

    /**
     */
    public function getTimestampFormat(): string
    {
        if (false === $this->has(Metadata::TIMESTAMP_FORMAT)) {
            throw new EventSourcingException('Timestamp Format is required.');
        }

        return $this->get(Metadata::TIMESTAMP_FORMAT);
    }

    /**
     */
    public function getAggregateId(): AggregateIdInterface
    {
        if (false === $this->has(Metadata::AGGREGATE_ID)) {
            throw new EventSourcingException('Aggregate ID is required.');
        }

        return AggregateId::fromString($this->get(Metadata::AGGREGATE_ID));
    }

    /**
     */
    public function getAggregateVersion(): AggregateVersionInterface
    {
        if (false === $this->has(Metadata::AGGREGATE_VERSION)) {
            throw new EventSourcingException('Aggregate Version is required.');
        }

        return AggregateVersion::fromInt((int) $this->get(Metadata::AGGREGATE_VERSION));
    }
}
