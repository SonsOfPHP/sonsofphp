<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler\MessageUpcasterHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * Event Type Message Upcaster Provider.
 *
 * This upcaster provider will return upcaster handlers based on the event
 * type. You are able to register many different event types and each event
 * type can have many handlers.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class EventTypeMessageUpcasterProvider implements MessageUpcasterProviderInterface
{
    private array $upcasters = [];

    public function register(string $eventType, MessageUpcasterHandlerInterface $upcaster): void
    {
        $this->upcasters[$eventType][] = $upcaster;
    }

    public function getUpcastersForEventData(array $eventData): iterable
    {
        if (empty($eventData['metadata'][Metadata::EVENT_TYPE])) {
            throw new EventSourcingException('Event Data does not have metadata.' . Metadata::EVENT_TYPE . ' set so this provider cannot be used');
        }

        $eventType = $eventData['metadata'][Metadata::EVENT_TYPE];

        if (isset($this->upcasters[$eventType])) {
            yield from $this->upcasters[$eventType];
        }
    }
}
