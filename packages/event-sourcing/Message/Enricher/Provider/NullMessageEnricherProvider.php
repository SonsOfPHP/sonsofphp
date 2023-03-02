<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Null Provider.
 *
 * Used when there isn't any need for message enrichers
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class NullMessageEnricherProvider implements MessageEnricherProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEnrichersForMessage(MessageInterface $message): iterable
    {
        return [];
    }
}
