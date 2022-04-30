<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Enricher
 *
 * This is used to add extra metadata to a message. Message should be
 * enriched before they are stored and before the message is dispatched
 * to handlers.
 *
 * Usage:
 *   $enricher = new MessageEnricher(new MessageEnricherProvider());
 *   $message = $enricher->enrich($message);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageEnricherInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    public function enrich(MessageInterface $message): MessageInterface;
}
