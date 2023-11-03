<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Serializer;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializer;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializer
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\EventTypeMessageEnricherHandler
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\AllMessageEnricherProvider
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializer
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Upcaster\MessageUpcaster
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Upcaster\Provider\NullMessageUpcasterProvider
 */
final class MessageSerializerTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $provider   = $this->createMock(MessageProviderInterface::class);
        $serializer = new MessageSerializer($provider);
        $this->assertInstanceOf(MessageSerializerInterface::class, $serializer);
    }

    /**
     * @covers ::ensureRequiredMetadataExists
     * @covers ::serialize
     */
    public function testSerialize(): void
    {
        $provider = $this->createMock(MessageProviderInterface::class);
        $provider->expects($this->once())->method('getEventTypeForMessage');
        $serializer = new MessageSerializer($provider);

        $message = FakeSerializableMessage::new()->withPayload([
            'key' => 'value',
        ])->withMetadata([
            Metadata::EVENT_ID          => 'event-id',
            Metadata::TIMESTAMP         => '2022-04-20',
            Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
            Metadata::AGGREGATE_ID      => 'aggregate-id',
            Metadata::AGGREGATE_VERSION => 123,
        ]);

        $data = $serializer->serialize($message);

        $this->assertArrayHasKey('payload', $data);
        $this->assertArrayHasKey('metadata', $data);

        $this->assertArrayHasKey(Metadata::EVENT_TYPE, $data['metadata'], 'Assert that "event_type" is added to metadata');
    }

    /**
     * @covers ::deserialize
     * @covers ::ensureRequiredMetadataExists
     */
    public function testDeserialize(): void
    {
        $data = [
            'payload'  => [],
            'metadata' => [
                Metadata::EVENT_TYPE        => 'user.registered',
                Metadata::EVENT_ID          => 'event-id',
                Metadata::TIMESTAMP         => '2022-04-20',
                Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
                Metadata::AGGREGATE_ID      => 'aggregate-id',
                Metadata::AGGREGATE_VERSION => 123,
            ],
        ];

        $provider = $this->createMock(MessageProviderInterface::class);
        $provider->expects($this->once())->method('getMessageClassForEventType')
            ->willReturn(FakeSerializableMessage::class);
        $serializer = new MessageSerializer($provider);

        $message = $serializer->deserialize($data);
        $this->assertInstanceOf(SerializableMessageInterface::class, $message);
    }
}
