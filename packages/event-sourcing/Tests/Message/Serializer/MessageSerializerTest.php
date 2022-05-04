<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Serializer;

use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializer;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageProviderInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;
use PHPUnit\Framework\TestCase;

final class MessageSerializerTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $provider   = $this->createMock(MessageProviderInterface::class);
        $serializer = new MessageSerializer($provider);
        $this->assertInstanceOf(MessageSerializerInterface::class, $serializer);
    }

    public function testSerialize(): void
    {
        $provider = $this->createMock(MessageProviderInterface::class);
        $provider->expects($this->once())->method('getEventTypeForMessage');
        $serializer = new MessageSerializer($provider);

        $message = $this->createMock(SerializableMessageInterface::class);
        $message->expects($this->once())->method('withMetadata')->willReturnSelf();
        $message->expects($this->once())->method('serialize');

        $data = $serializer->serialize($message);
    }

    public function testDeserialize(): void
    {
        $data = [
            'payload'  => [],
            'metadata' => [
                Metadata::EVENT_TYPE => 'user.registered',
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
