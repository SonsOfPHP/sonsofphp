<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;

/**
 * All Message Enricher
 *
 * This will enrich ALL messages and is not limited to specific message types
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AllMessageEnricherProvider implements MessageEnricherProviderInterface
{
    private array $enrichers = [];

    /**
     */
    public function register(MessageEnricherHandlerInterface $handler): void
    {
        $this->enrichers[] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnrichersForMessage(MessageInterface $message): iterable
    {
        return $this->enrichers;
    }
}
