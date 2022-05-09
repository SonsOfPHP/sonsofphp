<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * Event Type Enricher
 *
 * This will add the Event Type to the message
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class EventTypeMessageEnricherHandler implements  MessageEnricherHandlerInterface
{
    private MessageProviderInterface $messageProvider;

    /**
     * @param MessageProviderInterface $messageProvider
     */
    public function __construct(MessageProviderInterface $messageProvider)
    {
        $this->messageProvider = $messageProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function enrich(MessageInterface $message): MessageInterface
    {
        return $message->withMetadata([
            Metadata::EVENT_TYPE => $this->messageProvider->getEventTypeForMessage($message),
        ]);
    }
}
