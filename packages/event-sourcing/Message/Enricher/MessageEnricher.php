<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessageEnricher implements MessageEnricherInterface
{
    private MessageEnricherProviderInterface $provider;

    public function __construct(MessageEnricherProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        $enrichers = $this->provider->getEnrichersForMessage($message);
        foreach ($enrichers as $msgEnricher) {
            $message = $msgEnricher->enrich($message);
        }

        return $message;
    }
}
