<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Upcaster Handler Interface
 *
 * Before a message is persisted, it can be enriched with extra metadata. Any
 * class that needs to enrich the message must implement this interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageUpcasterHandlerInterface
{
    /**
     * Enrich and return the enriched message
     */
    public function upcast(MessageInterface $message): MessageInterface;
}
