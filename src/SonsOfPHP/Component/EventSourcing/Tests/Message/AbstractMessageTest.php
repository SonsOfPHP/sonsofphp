<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

class Msg extends AbstractMessage {}

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 */
final class AbstractMessageTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::new
     */
    public function testItHasTheRightInterface(): void
    {
        $message = Msg::new();

        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    /**
     * @covers ::getMetadata
     */
    public function testGetMetadataHasEmptyArraryAsDefaultValue(): void
    {
        $message = Msg::new();

        $this->assertCount(6, $message->getMetadata());
    }

    /**
     * @covers ::withMetadata
     */
    public function testWithMetadataReturnsNewStatic(): void
    {
        $message = Msg::new();

        $return = $message->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);
        $this->assertNotSame($return, $message);
    }

    /**
     * @covers ::withMetadata
     */
    public function testWithMetadataWorksCorrectly(): void
    {
        $message = Msg::new()->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);

        $this->assertArrayHasKey(Metadata::EVENT_TYPE, $message->getMetadata());
    }

    /**
     * @covers ::getAggregateId
     * @covers ::getAggregateVersion
     * @covers ::getEventId
     * @covers ::getEventType
     * @covers ::getTimestamp
     * @covers ::getTimestampFormat
     */
    public function testGettersWithEmptyMetadata(): void
    {
        $message = Msg::new();

        $this->expectException(EventSourcingException::class);
        $this->assertSame('', $message->getEventId());
        $this->assertSame('', $message->getEventType());
        $this->assertSame('', $message->getTimestamp());
        $this->assertSame(Metadata::DEFAULT_TIMESTAMP_FORMAT, $message->getTimestampFormat());
        $this->assertSame('', $message->getAggregateId()->toString());
        $this->assertSame(0, $message->getAggregateVersion()->toInt());
    }

    /**
     * @covers ::getAggregateId
     * @covers ::getAggregateVersion
     * @covers ::getEventId
     * @covers ::getEventType
     * @covers ::getTimestamp
     * @covers ::getTimestampFormat
     * @covers ::withMetadata
     */
    public function testGettersWithMetadata(): void
    {
        $message = Msg::new()->withMetadata([
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

    /**
     * @covers ::getAggregateId
     * @covers ::withMetadata
     */
    public function testGetAggregateIdReturnsCorrectInterface(): void
    {
        $message = Msg::new()->withMetadata([
            Metadata::AGGREGATE_ID => 'aggregate-id',
        ]);

        $this->assertInstanceOf(AggregateIdInterface::class, $message->getAggregateId());
    }

    /**
     * @covers ::getAggregateVersion
     * @covers ::withMetadata
     */
    public function testGetAggregateVersionReturnsCorrectInterface(): void
    {
        $message = Msg::new()->withMetadata([
            Metadata::AGGREGATE_VERSION => 123,
        ]);

        $this->assertInstanceOf(AggregateVersionInterface::class, $message->getAggregateVersion());
    }

    /**
     * @covers ::getPayload
     */
    public function testGetPayloadHasEmptyArraryAsDefaultValue(): void
    {
        $message = Msg::new();

        $this->assertCount(0, $message->getPayload());
    }

    /**
     * @covers ::withPayload
     */
    public function testWithPayloadReturnsNewStatic(): void
    {
        $message = Msg::new();

        $return = $message->withPayload([
            'key' => 'val',
        ]);
        $this->assertNotSame($return, $message);
    }

    /**
     * @covers ::withPayload
     */
    public function testWithPayloadWorksCorrectly(): void
    {
        $message = Msg::new()->withPayload([
            'key' => 'val',
        ]);

        $this->assertArrayHasKey('key', $message->getPayload());
    }
}
