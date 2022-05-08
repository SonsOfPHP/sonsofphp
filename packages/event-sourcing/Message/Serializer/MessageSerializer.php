<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricherInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\NullMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcasterInterface;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\NullMessageUpcasterProvider;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageSerializer implements MessageSerializerInterface
{
    private MessageProviderInterface $messageProvider;
    private MessageEnricherInterface $messageEnricher;
    private MessageUpcasterInterface $messageUpcaster;

    public function __construct(MessageProviderInterface $messageProvider)
    {
        $this->messageProvider = $messageProvider;
        $this->messageEnricher = new MessageEnricher(new NullMessageEnricherProvider());
        $this->messageUpcaster = new MessageUpcaster(new NullMessageUpcasterProvider());
    }

    /**
     */
    public function serialize(SerializableMessageInterface $message): array
    {
        $message = $this->messageEnricher->enrich($message);
        $message = $message->withMetadata([
            Metadata::EVENT_TYPE => $this->messageProvider->getEventTypeForMessage($message),
        ]);

        return $message->serialize();
    }

    /**
     */
    public function deserialize(array $data): SerializableMessageInterface
    {
        $data         = $this->messageUpcaster->upcast($data);
        $messageClass = $this->messageProvider
            ->getMessageClassForEventType($data['metadata'][Metadata::EVENT_TYPE]);

        return $messageClass::deserialize($data);
    }
}
