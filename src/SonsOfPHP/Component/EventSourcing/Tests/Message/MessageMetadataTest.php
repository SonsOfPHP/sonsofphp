<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Message\MessageMetadata;
use SonsOfPHP\Component\EventSourcing\Metadata;

#[CoversClass(MessageMetadata::class)]
#[UsesClass(AbstractAggregateId::class)]
#[UsesClass(AggregateVersion::class)]
final class MessageMetadataTest extends TestCase
{
    public function testAllTheThings(): void
    {
        $metadata = new MessageMetadata([
            Metadata::EVENT_ID          => '1234',
            Metadata::EVENT_TYPE        => '5678',
            Metadata::AGGREGATE_ID      => '9012',
            Metadata::AGGREGATE_VERSION => 420,
            Metadata::TIMESTAMP         => '2022-04-20 04:20',
            Metadata::TIMESTAMP_FORMAT  => 'c',
        ]);
        $this->assertCount(6, $metadata);

        $this->assertSame('1234', $metadata->getEventId());
        $this->assertSame('5678', $metadata->getEventType());
        $this->assertSame('9012', $metadata->getAggregateId()->toString());
        $this->assertSame(420, $metadata->getAggregateVersion()->toInt());
        $this->assertSame('2022-04-20 04:20', $metadata->getTimestamp()->format('Y-m-d H:i'));
        $this->assertSame('c', $metadata->getTimestampFormat());

        $another = $metadata->with('your_mom', 'has the clap');

        $this->assertNotSame($metadata, $another);
        $this->assertCount(7, $another);
    }
}
