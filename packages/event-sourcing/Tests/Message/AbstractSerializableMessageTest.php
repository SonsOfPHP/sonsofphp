<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage
 */
final class AbstractSerializableMessageTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterfaces(): void
    {
        $message = FakeSerializableMessage::new();
        $this->assertInstanceOf(MessageInterface::class, $message); // @phpstan-ignore-line
        $this->assertInstanceOf(SerializableMessageInterface::class, $message); // @phpstan-ignore-line
    }

    /**
     * @covers ::serialize
     */
    public function testSerializeOnEmptyMessage(): void
    {
        $message = FakeSerializableMessage::new();
        $return = $message->serialize();
        $this->assertArrayHasKey('payload', $return);
        $this->assertArrayHasKey('metadata', $return);
    }

    /**
     * @covers ::deserialize
     */
    public function testDeserializeWithEmptyData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([]);
    }

    /**
     * @covers ::deserialize
     */
    public function testDeserializeWithNoPayloadData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'metadata' => [],
        ]);
    }

    /**
     * @covers ::deserialize
     */
    public function testDeserializeWithNoMetadataData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'payload' => [],
        ]);
    }

    /**
     * @covers ::deserialize
     */
    public function testDeserialize(): void
    {
        $message = FakeSerializableMessage::new();
        $msg = $message::deserialize([
            'payload' => [
                'key' => 'value',
            ],
            'metadata' => [
                Metadata::EVENT_ID => 'event-id',
                Metadata::EVENT_TYPE => 'event.type',
                Metadata::TIMESTAMP => '2022-04-20',
                Metadata::TIMESTAMP_FORMAT => 'Y-m-d',
                Metadata::AGGREGATE_ID => 'aggregate-id',
                Metadata::AGGREGATE_VERSION => 123,
            ],
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
