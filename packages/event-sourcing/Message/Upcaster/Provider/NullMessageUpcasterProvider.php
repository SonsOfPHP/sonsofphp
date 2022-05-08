<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullMessageUpcasterProvider implements MessageUpcasterProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUpcastersForEventData(array $eventData): iterable
    {
        return [];
    }
}
