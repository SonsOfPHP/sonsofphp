<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;

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
     * @todo Should this be part of the interface?
     */
    //public function register(MessageEnricherHandlerInterface $handler): void

    /**
     * @param MessageInterface $message
     *
     * @return iterable
     */
    public function getEnrichersForMessage(MessageInterface $message): iterable;
}
