<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;

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
    final public function __construct(
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
    final public function getEventId(): string
    {
        return $this->metadata->getEventId();
    }

    /**
     * {@inheritdoc}
     */
    final public function getEventType(): string
    {
        return $this->metadata->getEventType();
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestamp(): string
    {
        return $this->metadata->getTimestamp()->format($this->getTimestampFormat());
    }

    /**
     * {@inheritdoc}
     */
    final public function getTimestampFormat(): string
    {
        return $this->metadata->getTimestampFormat();
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateId(): AggregateIdInterface
    {
        return $this->metadata->getAggregateId();
    }

    /**
     * {@inheritdoc}
     */
    final public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->metadata->getAggregateVersion();
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
