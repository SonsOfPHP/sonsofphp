<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Upcaster Provider
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterProviderInterface
{
    /**
     */
    public function getUpcastersForMessage(MessageInterface $message): iterable;
}
