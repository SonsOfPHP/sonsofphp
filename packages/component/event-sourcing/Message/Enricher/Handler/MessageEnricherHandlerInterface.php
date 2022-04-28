<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Enricher Handler Interface
 *
 * Before a message is persisted, it can be enriched with extra metadata. Any
 * class that needs to enrich the message must implement this interface.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageEnricherHandlerInterface
{
    /**
     * Enrich and return the enriched message
     */
    public function enrich(MessageInterface $message): MessageInterface;
}
