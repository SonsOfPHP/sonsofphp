<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher;

use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\MessageEnricherProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final readonly class MessageEnricher implements MessageEnricherInterface
{
    public function __construct(private MessageEnricherProviderInterface $provider) {}

    public function enrich(MessageInterface $message): MessageInterface
    {
        $enrichers = $this->provider->getEnrichersForMessage($message);
        foreach ($enrichers as $msgEnricher) {
            $message = $msgEnricher->enrich($message);
        }

        return $message;
    }
}
