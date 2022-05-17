<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;

/**
 * Message Upcaster Provider.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterProviderInterface
{
    /**
     * @throw EventSourcingException
     */
    public function getUpcastersForEventData(array $eventData): iterable;
}
