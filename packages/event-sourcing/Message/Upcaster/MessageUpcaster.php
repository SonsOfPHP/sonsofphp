<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\MessageUpcasterProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessageUpcaster implements MessageUpcasterInterface
{
    private MessageUpcasterProviderInterface $provider;

    public function __construct(MessageUpcasterProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function upcast(array $data): array
    {
        $handlers = $this->provider->getUpcastersForEventData($data);
        foreach ($handlers as $handler) {
            $data = $handler->upcast($data);
        }

        return $data;
    }
}
