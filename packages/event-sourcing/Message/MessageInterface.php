<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;

/**
 * Domain Event Message.
 *
 * The aggregate will raise these events and these are the events that will be
 * applied to the aggregate.
 *
 * These messages are dispatched on the event bus.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageInterface
{
    /**
     * Returns new Message Interface.
     *
     * @return static
     */
    public static function new(): MessageInterface;

    /**
     * Returns the Aggregate ID, if the aggregate ID is unknown, it
     * will return null.
     */
    public function getAggregateId(): ?AggregateIdInterface;

    /**
     * Returns the Aggregate Version, if the aggregate Version is unknown, it
     * will return null.
     */
    public function getAggregateVersion(): ?AggregateVersionInterface;

    public function getEventId(): ?string;

    public function getEventType(): ?string;

    public function getTimestamp(): ?string;

    public function getTimestampFormat(): ?string;

    /**
     * @return static
     */
    public function withMetadata(array $metadata): MessageInterface;

    /**
     * Returns the metadata for this message.
     *
     * Metadata is the extra information about the message. For example, the
     * Event Type is stored in the metadata.
     */
    public function getMetadata(): array;

    /**
     * @return static
     */
    public function withPayload(array $payload): MessageInterface;

    /**
     * Returns the payload.
     *
     * The payload is the data required be a handler.
     */
    public function getPayload(): array;
}
