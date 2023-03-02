<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullMessageUpcasterProvider implements MessageUpcasterProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUpcastersForEventData(array $eventData): iterable
    {
        return [];
    }
}
