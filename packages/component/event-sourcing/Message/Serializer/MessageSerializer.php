<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageSerializer implements MessageSerializerInterface
{
    private MessageProviderInterface $messageProvider;

    public function __construct(MessageProviderInterface $messageProvider)
    {
        $this->messageProvider = $messageProvider;
        // upcaster
    }

    /**
     */
    public function serialize(MessageInterface $message): array
    {
        $message = $message->withMetadata([
            Metadata::EVENT_TYPE => $this->messageProvider->getEventTypeForMessage($message),
        ]);

        return [
            'payload'  => $message->getPayload(),
            'metadata' => $message->getMetadata(),
        ];
    }

    /**
     */
    public function deserialize(array $data): MessageInterface
    {
        $eventType = $data['metadata'][Metadata::EVENT_TYPE];
        // upcast data
        // $data = $this->messageUpcaster->upcast($eventType, $data);
    }
}
