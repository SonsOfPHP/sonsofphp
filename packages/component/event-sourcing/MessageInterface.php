<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

/**
 * Domain Event Message
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
     * Returns new Message Interface
     *
     * @return static
     */
    public static function new(): MessageInterface;

    /**
     * @return AggregateIdInterface|null
     */
    public function getAggregateId(): ?AggregateIdInterface;

    /**
     * @return AggregateVersionInterface|null
     */
    public function getAggregateVersion(): ?AggregateVersionInterface;

    /**
     */
    public function getEventId(): ?string;

    /**
     */
    public function getEventType(): ?string;

    /**
     */
    public function getTimestamp(): ?string;

    /**
     */
    public function getTimestampFormat(): ?string;

    /**
     * @param array $metadata
     *
     * @return static
     */
    public function withMetadata(array $metadata): MessageInterface;

    /**
     * Returns the metadata for this message
     *
     * @return array
     */
    public function getMetadata(): array;
}
