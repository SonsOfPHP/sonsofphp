<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * Abstract Domain Event Message.
 *
 * Usage:
 *   $message = MessageClass::new();
 *   $message = MessageClass::new()->withPayload($payload);
 *   $message = MessageClass::new()->withPayload($payload)->withMetadata($metadata);
 *   $message = new MessageClass();
 *   $message = new MessageClass($payload);
 *   $message = new MessageClass($payload, $metadata);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractMessage implements MessageInterface
{
    private array $payload = [];
    private array $metadata = [];

    /**
     * @param array $payload
     * @param array $metadata
     */
    final private function __construct(?array $payload = null, ?array $metadata = null)
    {
        $this->payload = $payload ?? [];
        $this->metadata = $metadata ?? [];
    }

    /**
     * {@inheritdoc}
     */
    final public static function new(): MessageInterface
    {
        return new static();
    }

    /**
     * {@inheritdoc}
     */
    final public function getEventId(): ?string
    {
        if (isset($this->metadata[Metadata::EVENT_ID])) {
            return (string) $this->metadata[Metadata::EVENT_ID];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getEventType(): ?string
    {
        if (isset($this->metadata[Metadata::EVENT_TYPE])) {
            return (string) $this->metadata[Metadata::EVENT_TYPE];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestamp(): ?string
    {
        if (isset($this->metadata[Metadata::TIMESTAMP])) {
            return (string) $this->metadata[Metadata::TIMESTAMP];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestampFormat(): ?string
    {
        if (isset($this->metadata[Metadata::TIMESTAMP_FORMAT])) {
            return (string) $this->metadata[Metadata::TIMESTAMP_FORMAT];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateId(): ?AggregateIdInterface
    {
        if (isset($this->metadata[Metadata::AGGREGATE_ID])) {
            return AggregateId::fromString((string) $this->metadata[Metadata::AGGREGATE_ID]);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateVersion(): ?AggregateVersionInterface
    {
        if (isset($this->metadata[Metadata::AGGREGATE_VERSION])) {
            return AggregateVersion::fromInt((int) $this->metadata[Metadata::AGGREGATE_VERSION]);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function withMetadata(array $metadata): MessageInterface
    {
        $that = clone $this;
        $that->metadata = array_merge($this->metadata, $metadata);

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    final public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    final public function withPayload(array $payload): MessageInterface
    {
        $that = clone $this;
        $that->payload = array_merge($this->payload, $payload);

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    final public function getPayload(): array
    {
        return $this->payload;
    }
}
