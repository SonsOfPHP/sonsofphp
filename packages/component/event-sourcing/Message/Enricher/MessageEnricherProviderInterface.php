<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Enricher Provider
 *
 * The responsibility of the provider is to return the Enrichers that
 * can enrich the message.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageEnricherProviderInterface
{
    /**
     */
    public function getEnrichersForMessage(MessageInterface $message): iterable;
}
