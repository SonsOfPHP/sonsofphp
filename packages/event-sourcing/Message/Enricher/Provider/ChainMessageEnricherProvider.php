<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Chain Message Enricher Provider.
 *
 * Allows you to use multiple Message Enricher Providers
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class ChainMessageEnricherProvider implements MessageEnricherProviderInterface
{
    private array $providers = [];

    /**
     * @param MessageEnricherProviderInterface[] $providers
     */
    public function __construct(?array $providers = [])
    {
        foreach ($providers as $provider) {
            $this->registerProvider($provider);
        }
    }

    public function registerProvider(MessageEnricherProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function getEnrichersForMessage(MessageInterface $message): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getEnrichersForMessage($message);
        }
    }
}
