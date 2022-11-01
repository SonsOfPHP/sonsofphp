<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\EventTypeMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricherInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcasterInterface;
use SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\NullMessageUpcasterProvider;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageSerializer implements MessageSerializerInterface
{
    private MessageProviderInterface $messageProvider;
    private MessageEnricherInterface $messageEnricher;
    private MessageUpcasterInterface $messageUpcaster;

    /**
     * @param MessageEnricherInterface $messageEnricher
     * @param MessageUpcasterInterface $messageUpcaster
     */
    public function __construct(
        MessageProviderInterface $messageProvider,
        MessageEnricherInterface $messageEnricher = null,
        MessageUpcasterInterface $messageUpcaster = null
    ) {
        $this->messageProvider = $messageProvider;
        $this->messageEnricher = $messageEnricher ?? new MessageEnricher(new AllMessageEnricherProvider([new EventTypeMessageEnricherHandler($this->messageProvider)]));
        $this->messageUpcaster = $messageUpcaster ?? new MessageUpcaster(new NullMessageUpcasterProvider());
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(SerializableMessageInterface $message): array
    {
        // @var SerializableMessageInterface $message
        $message = $this->messageEnricher->enrich($message);

        $this->ensureRequiredMetadataExists($message->getMetadata());

        return $message->serialize(); // @phpstan-ignore-line
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize(array $data): SerializableMessageInterface
    {
        $data = $this->messageUpcaster->upcast($data);

        $this->ensureRequiredMetadataExists($data['metadata']);

        $messageClass = $this->messageProvider
            ->getMessageClassForEventType($data['metadata'][Metadata::EVENT_TYPE]);

        return $messageClass::deserialize($data);
    }

    /**
     * @internal
     *
     * @throws EventSourcingException
     */
    private function ensureRequiredMetadataExists(array $metadata): void
    {
        $requiredMetadata = [
            Metadata::EVENT_ID,
            Metadata::EVENT_TYPE,
            Metadata::AGGREGATE_ID,
            Metadata::AGGREGATE_VERSION,
            Metadata::TIMESTAMP,
            Metadata::TIMESTAMP_FORMAT,
        ];

        if (\count($requiredMetadata) != \count(array_intersect_key(array_flip($requiredMetadata), $metadata))) {
            $values = [];
            foreach ($metadata as $k => $v) {
                $values[] = $k.' => '.$v;
            }
            throw new EventSourcingException('Message Metadata is missing one or more required values. Current metadata: '.implode(',', $values));
        }
    }
}
