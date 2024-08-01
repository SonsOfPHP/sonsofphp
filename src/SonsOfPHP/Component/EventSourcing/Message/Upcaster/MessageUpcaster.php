<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\MessageUpcasterProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class MessageUpcaster implements MessageUpcasterInterface
{
    public function __construct(private readonly MessageUpcasterProviderInterface $provider) {}

    public function upcast(array $data): array
    {
        $handlers = $this->provider->getUpcastersForEventData($data);
        foreach ($handlers as $handler) {
            $data = $handler->upcast($data);
        }

        return $data;
    }
}
