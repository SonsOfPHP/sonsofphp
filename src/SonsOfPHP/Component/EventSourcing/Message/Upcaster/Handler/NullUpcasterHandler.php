<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler;

/**
 * Null Upcast Handler.
 *
 * This handler will just pass data through it.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullUpcasterHandler implements MessageUpcasterHandlerInterface
{
    public function upcast(array $eventData): array
    {
        return $eventData;
    }
}
