<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @coversNothing
 */
#[CoversClass(AbstractMessage::class)]
final class AbstractMessageTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $message = $this->createMock(AbstractMessage::class);

        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testGetMetadataHasEmptyArraryAsDefaultValue(): void
    {
        $message = $this->createMock(AbstractMessage::class)::new();

        $this->assertCount(6, $message->getMetadata());
    }

    public function testWithMetadataReturnsNewStatic(): void
    {
        $message = $this->createMock(AbstractMessage::class)::new();

        $return = $message->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);
        $this->assertNotSame($return, $message);
    }

    public function testWithMetadataWorksCorrectly(): void
    {
        $message = $this->createMock(AbstractMessage::class);
        $message = $message::new()->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);

        $this->assertArrayHasKey(Metadata::EVENT_TYPE, $message->getMetadata());
    }

    public function testGettersWithEmptyMetadata(): void
    {
        $message = $this->createMock(AbstractMessage::class)::new();

        $this->expectException(EventSourcingException::class);
        $this->assertSame('', $message->getEventId());
        $this->assertSame('', $message->getEventType());
        $this->assertSame('', $message->getTimestamp());
        $this->assertSame(Metadata::DEFAULT_TIMESTAMP_FORMAT, $message->getTimestampFormat());
        $this->assertSame('', $message->getAggregateId()->toString());
        $this->assertSame(0, $message->getAggregateVersion()->toInt());
    }

    public function testGettersWithMetadata(): void
    {
        $message = $this->createMock(AbstractMessage::class);
        $message = $message::new()->withMetadata([
            Metadata::EVENT_ID          => 'event-id',
            Metadata::EVENT_TYPE        => 'event.type',
            Metadata::TIMESTAMP         => '2022-04-20',
            Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
            Metadata::AGGREGATE_ID      => 'aggregate-id',
            Metadata::AGGREGATE_VERSION => 123,
        ]);

        $this->assertSame('event-id', $message->getEventId());
        $this->assertSame('event.type', $message->getEventType());
        $this->assertSame('2022-04-20', $message->getTimestamp());
        $this->assertSame('Y-m-d', $message->getTimestampFormat());
        $this->assertSame('aggregate-id', $message->getAggregateId()->toString());
        $this->assertSame(123, $message->getAggregateVersion()->toInt());
    }

    public function testGetAggregateIdReturnsCorrectInterface(): void
    {
        $message = $this->createMock(AbstractMessage::class);
        $message = $message::new()->withMetadata([
            Metadata::AGGREGATE_ID => 'aggregate-id',
        ]);

        $this->assertInstanceOf(AggregateIdInterface::class, $message->getAggregateId());
    }

    public function testGetAggregateVersionReturnsCorrectInterface(): void
    {
        $message = $this->createMock(AbstractMessage::class);
        $message = $message::new()->withMetadata([
            Metadata::AGGREGATE_VERSION => 123,
        ]);

        $this->assertInstanceOf(AggregateVersionInterface::class, $message->getAggregateVersion());
    }

    public function testGetPayloadHasEmptyArraryAsDefaultValue(): void
    {
        $message = $this->createMock(AbstractMessage::class)::new();

        $this->assertCount(0, $message->getPayload());
    }

    public function testWithPayloadReturnsNewStatic(): void
    {
        $message = $this->createMock(AbstractMessage::class)::new();

        $return = $message->withPayload([
            'key' => 'val',
        ]);
        $this->assertNotSame($return, $message);
    }

    public function testWithPayloadWorksCorrectly(): void
    {
        $message = $this->createMock(AbstractMessage::class);
        $message = $message::new()->withPayload([
            'key' => 'val',
        ]);

        $this->assertArrayHasKey('key', $message->getPayload());
    }
}
