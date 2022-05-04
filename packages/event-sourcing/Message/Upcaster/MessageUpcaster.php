<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Upcaster;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

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
    public function upcast(MessageInterface $message): MessageInterface
    {
        $upcasters = $this->provider->getUpcastersForMessage($message);
        foreach ($upcasters as $msgUpcaster) {
            $message = $msgUpcaster->upcast($message);
        }

        return $message;
    }
}
