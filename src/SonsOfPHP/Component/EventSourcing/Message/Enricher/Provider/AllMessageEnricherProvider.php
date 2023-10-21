<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * All Message Enricher Provider.
 *
 * This will enrich ALL messages and is not limited to specific message types
 *
 * @todo All vs Any? Should the class name be updated?
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AllMessageEnricherProvider implements MessageEnricherProviderInterface
{
    private array $enrichers = [];

    public function __construct(array $enrichers = [])
    {
        foreach ($enrichers as $enricher) {
            $this->register($enricher);
        }
    }

    public function register(MessageEnricherHandlerInterface $handler): void
    {
        $this->enrichers[] = $handler;
    }

    public function getEnrichersForMessage(MessageInterface $message): iterable
    {
        return $this->enrichers;
    }
}
