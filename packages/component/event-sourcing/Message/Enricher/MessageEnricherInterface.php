<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Enricher Interface
 *
 * Before a message is persisted, it can be enriched with extra metadata.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageEnricherInterface
{
    /**
     * Enrich and return the enriched message
     */
    public function enrich(MessageInterface $message): MessageInterface;
}
