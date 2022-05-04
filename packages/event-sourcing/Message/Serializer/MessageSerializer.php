<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

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
    public function serialize(SerializableMessageInterface $message): array
    {
        $message = $message->withMetadata([
            Metadata::EVENT_TYPE => $this->messageProvider->getEventTypeForMessage($message),
        ]);

        return $message->serialize();
    }

    /**
     */
    public function deserialize(array $data): SerializableMessageInterface
    {
        // upcast data
        // $data = $this->messageUpcaster->upcast($data);
        $eventType = $data['metadata'][Metadata::EVENT_TYPE];

        $messageClass = $this->messageProvider->getMessageClassForEventType($eventType);

        return $messageClass::deserialize($data);
    }
}
