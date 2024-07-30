<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;

/**
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @coversNothing
 */
#[CoversClass(AbstractSerializableMessage::class)]
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

    public function testSerializeOnEmptyMessage(): void
    {
        $message = FakeSerializableMessage::new();
        $return  = $message->serialize();
        $this->assertArrayHasKey('payload', $return);
        $this->assertArrayHasKey('metadata', $return);
    }

    public function testDeserializeWithEmptyData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([]);
    }

    public function testDeserializeWithNoPayloadData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'metadata' => [],
        ]);
    }

    public function testDeserializeWithNoMetadataData(): void
    {
        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $message::deserialize([
            'payload' => [],
        ]);
    }

    public function testDeserialize(): void
    {
        $message = FakeSerializableMessage::new();
        $msg     = $message::deserialize([
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
