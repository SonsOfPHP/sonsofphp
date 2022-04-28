<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
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
