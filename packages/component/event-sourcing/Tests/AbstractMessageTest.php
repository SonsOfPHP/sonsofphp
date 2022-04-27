<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use PHPUnit\Framework\TestCase;

final class AbstractMessageTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false);
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testGetMetadataHasEmptyArraryAsDefaultValue(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false);
        $this->assertIsArray($message->getMetadata());
        $this->assertCount(0, $message->getMetadata());
    }

    public function testWithMetadataReturnsNewStatic(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false);
        $return = $message->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);
        $this->assertNotSame($return, $message);
    }

    public function testWithMetadataWorksCorrectly(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false)->withMetadata([
            Metadata::EVENT_TYPE => 'test',
        ]);

        $this->assertArrayHasKey(Metadata::EVENT_TYPE, $message->getMetadata());
    }

    public function testGettersWithEmptyMetadata(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false);

        $this->assertNull($message->getEventId());
        $this->assertNull($message->getEventType());
        $this->assertNull($message->getTimestamp());
        $this->assertNull($message->getTimestampFormat());
        $this->assertNull($message->getAggregateId());
        $this->assertNull($message->getAggregateVersion());
    }

    public function testGettersWithMetadata(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false)->withMetadata([
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
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false)->withMetadata([
            Metadata::AGGREGATE_ID => 'aggregate-id',
        ]);

        $this->assertInstanceOf(AggregateIdInterface::class, $message->getAggregateId());
    }

    public function testGetAggregateVersionReturnsCorrectInterface(): void
    {
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [], '', false)->withMetadata([
            Metadata::AGGREGATE_VERSION => 123,
        ]);

        $this->assertInstanceOf(AggregateVersionInterface::class, $message->getAggregateVersion());
    }
}
