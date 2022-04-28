<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Provider Interface
 *
 * A message provider is responsible for mapping a Message to an Event Type and
 * an Event Type to a Message FQCN.
 *
 * As code grows, there could be many event types that map to a single Message
 * class
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageProviderInterface
{
    /**
     * Returns the event type for the message
     *
     * @param MessageInterface|string
     *
     * @throws EventSourcingException If there is no event type defined for this message
     */
    public function getEventTypeForMessage($message): string;

    /**
     * Returns the FQCN for the Message
     *
     * @throws EventSourcingException If there is no Message Class for the event type
     */
    public function getMessageClassForEventType(string $eventType): string;
}
