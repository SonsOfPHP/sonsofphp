<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster\Handler;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Null Upcast Handler
 *
 * This handler will just pass data through it.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullUpcasterHandler implements MessageUpcasterHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function upcast(array $eventData): array
    {
        return $eventData;
    }
}
