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
    ) {}

    final public static function new(): MessageInterface
    {
        return new static(new MessagePayload(), new MessageMetadata());
    }

    final public function getEventId(): string
    {
        return $this->metadata->getEventId();
    }

    final public function getEventType(): string
    {
        return $this->metadata->getEventType();
    }

    final public function getTimestamp(): string
    {
        return $this->metadata->getTimestamp()->format($this->getTimestampFormat());
    }

    final public function getTimestampFormat(): string
    {
        return $this->metadata->getTimestampFormat();
    }

    final public function getAggregateId(): AggregateIdInterface
    {
        return $this->metadata->getAggregateId();
    }

    final public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->metadata->getAggregateVersion();
    }

    final public function withMetadata(array $metadata): MessageInterface
    {
        $that           = clone $this;
        $that->metadata = new MessageMetadata(array_merge($this->metadata->all(), $metadata));

        return $that;
    }

    final public function getMetadata(): array
    {
        return $this->metadata->all();
    }

    final public function withPayload(array $payload): MessageInterface
    {
        $that          = clone $this;
        $that->payload = new MessagePayload(array_merge($this->payload->all(), $payload));

        return $that;
    }

    final public function getPayload(): array
    {
        return $this->payload->all();
    }
}
