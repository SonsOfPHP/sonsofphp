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
    final private function __construct(
        private MessagePayload $payload = new MessagePayload(),
        private MessageMetadata $metadata = new MessageMetadata(),
    ) {
    }

    /**
     * {@inheritdoc}
     */
    final public static function new(): MessageInterface
    {
        return new static(new MessagePayload(), new MessageMetadata());
    }

    /**
     * {@inheritdoc}
     */
    final public function getEventId(): ?string
    {
        if (true === $this->metadata->has(Metadata::EVENT_ID)) {
            return (string) $this->metadata->get(Metadata::EVENT_ID);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getEventType(): ?string
    {
        if (true === $this->metadata->has(Metadata::EVENT_TYPE)) {
            return (string) $this->metadata->get(Metadata::EVENT_TYPE);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestamp(): ?string
    {
        if (true === $this->metadata->has(Metadata::TIMESTAMP)) {
            return (string) $this->metadata->get(Metadata::TIMESTAMP);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestampFormat(): ?string
    {
        if (true === $this->metadata->has(Metadata::TIMESTAMP_FORMAT)) {
            return (string) $this->metadata->get(Metadata::TIMESTAMP_FORMAT);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateId(): ?AggregateIdInterface
    {
        if (true === $this->metadata->has(Metadata::AGGREGATE_ID)) {
            return AggregateId::fromString((string) $this->metadata->get(Metadata::AGGREGATE_ID));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateVersion(): ?AggregateVersionInterface
    {
        if (true === $this->metadata->has(Metadata::AGGREGATE_VERSION)) {
            return AggregateVersion::fromInt((int) $this->metadata->get(Metadata::AGGREGATE_VERSION));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    final public function withMetadata(array $metadata): MessageInterface
    {
        $that = clone $this;
        $that->metadata = new MessageMetadata(array_merge($this->metadata->all(), $metadata));

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    final public function getMetadata(): array
    {
        return $this->metadata->all();
    }

    /**
     * {@inheritdoc}
     */
    final public function withPayload(array $payload): MessageInterface
    {
        $that = clone $this;
        $that->payload = new MessagePayload(array_merge($this->payload->all(), $payload));

        return $that;
    }

    /**
     * {@inheritdoc}
     */
    final public function getPayload(): array
    {
        return $this->payload->all();
    }
}
