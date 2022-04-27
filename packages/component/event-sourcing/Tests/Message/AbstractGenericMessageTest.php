<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractGenericMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use PHPUnit\Framework\TestCase;

final class AbstractGenericMessageTest extends TestCase
{
    public function testItHasTheRightInterfaces(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $this->assertInstanceOf(MessageInterface::class, $message);
        $this->assertInstanceOf(SerializableMessageInterface::class, $message);
    }

    public function testGetPayloadHasEmptyArraryAsDefaultValue(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $this->assertIsArray($message->getPayload());
        $this->assertCount(0, $message->getPayload());
    }

    public function testWithPayloadReturnsNewStatic(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $return = $message->withPayload([
            'key' => 'val',
        ]);
        $this->assertNotSame($return, $message);
    }

    public function testWithPayloadWorksCorrectly(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false)->withPayload([
            'key' => 'val',
        ]);

        $this->assertArrayHasKey('key', $message->getPayload());
    }

    public function testSerializeOnEmptyMessage(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $return = $message->serialize();
        $this->assertArrayHasKey('payload', $return);
        $this->assertArrayHasKey('metadata', $return);
    }

    public function testDeserializeWithEmptyData(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $this->expectException(EventSourcingException::class);
        $message::deserialize([]);
    }

    public function testDeserializeWithNoPayloadData(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'metadata' => [],
        ]);
    }

    public function testDeserializeWithNoMetadataData(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'payload' => [],
        ]);
    }

    public function testDeserialize(): void
    {
        $message = $this->getMockForAbstractClass(AbstractGenericMessage::class, [], '', false);
        $msg = $message::deserialize([
            'payload' => [
                'key' => 'value',
            ],
            'metadata' => [
                Metadata::EVENT_ID          => 'event-id',
                Metadata::EVENT_TYPE        => 'event.type',
                Metadata::TIMESTAMP         => '2022-04-20',
                Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
                Metadata::AGGREGATE_ID      => 'aggregate-id',
                Metadata::AGGREGATE_VERSION => 123,
            ]
        ]);

        $this->assertArrayHasKey('key', $msg->getPayload());
        $this->assertSame('value', $msg->getPayload()['key']);

        $this->assertSame('event-id', $msg->getEventId());
        $this->assertSame('event.type', $msg->getEventType());
        $this->assertSame('2022-04-20', $msg->getTimestamp());
        $this->assertSame('Y-m-d', $msg->getTimestampFormat());
        $this->assertSame('aggregate-id', $msg->getAggregateId()->toString());
        $this->assertSame(123, $msg->getAggregateVersion()->toInt());
    }
}
