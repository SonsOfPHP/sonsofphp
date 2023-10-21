<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\EventSourcing\TableSchemaV1;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Doctrine\EventSourcing\TableSchemaV1
 *
 * @internal
 */
final class TableSchemaV1Test extends TestCase
{
    /**
     * @covers ::getTableName
     */
    public function testGetTableName(): void
    {
        $schema = new TableSchemaV1();

        $this->assertSame('event_store', $schema->getTableName());
    }

    /**
     * @covers ::getAggregateIdColumn
     */
    public function testGetAggregateIdColumn(): void
    {
        $schema = new TableSchemaV1();

        $this->assertSame('aggregate_root_id', $schema->getAggregateIdColumn());
    }

    /**
     * @covers ::getAggregateVersionColumn
     */
    public function testGetAggregateVersionColumn(): void
    {
        $schema = new TableSchemaV1();

        $this->assertSame('aggregate_root_version', $schema->getAggregateVersionColumn());
    }

    /**
     * @covers ::getColumns
     */
    public function testGetColumns(): void
    {
        $schema = new TableSchemaV1();

        $columns = $schema->getColumns();

        $this->assertArrayHasKey('event_id', $columns);
        $this->assertSame('string', $columns['event_id']->getName());

        $this->assertArrayHasKey('event_type', $columns);
        $this->assertSame('string', $columns['event_type']->getName());

        $this->assertArrayHasKey('aggregate_root_id', $columns);
        $this->assertSame('string', $columns['aggregate_root_id']->getName());

        $this->assertArrayHasKey('aggregate_root_version', $columns);
        $this->assertSame('integer', $columns['aggregate_root_version']->getName());

        $this->assertArrayHasKey('created_at', $columns);
        $this->assertSame('datetime', $columns['created_at']->getName());

        $this->assertArrayHasKey('payload', $columns);
        $this->assertSame('array', $columns['payload']->getName());

        $this->assertArrayHasKey('metadata', $columns);
        $this->assertSame('array', $columns['metadata']->getName());
    }

    /**
     * @covers ::mapEventDataToColumns
     */
    public function testMapEventDataToColumns(): void
    {
        $schema = new TableSchemaV1();

        $eventData = [
            'metadata' => [
                Metadata::EVENT_ID          => 'event-id',
                Metadata::EVENT_TYPE        => 'event-type',
                Metadata::TIMESTAMP         => '2022-04-20',
                Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
                Metadata::AGGREGATE_ID      => 'aggregate-id',
                Metadata::AGGREGATE_VERSION => 123,
            ],
            'payload' => [],
        ];

        $output = $schema->mapEventDataToColumns($eventData);

        $this->assertSame('event-id', $output['event_id']);
        $this->assertSame('event-type', $output['event_type']);
        $this->assertSame('aggregate-id', $output['aggregate_root_id']);
        $this->assertSame(123, $output['aggregate_root_version']);
        $this->assertSame($eventData['payload'], $output['payload']);
        $this->assertSame($eventData['metadata'], $output['metadata']);
        $this->assertInstanceOf(\DateTimeImmutable::class, $output['created_at']);
    }

    /**
     * @covers ::mapColumnsToEventData
     */
    public function testMapColumnsToEventData(): void
    {
        $schema = new TableSchemaV1();

        $result = [
            'metadata' => serialize([
                Metadata::EVENT_ID          => 'event-id',
                Metadata::EVENT_TYPE        => 'event-type',
                Metadata::TIMESTAMP         => '2022-04-20',
                Metadata::TIMESTAMP_FORMAT  => 'Y-m-d',
                Metadata::AGGREGATE_ID      => 'aggregate-id',
                Metadata::AGGREGATE_VERSION => 123,
            ]),
            'payload' => serialize([]),
        ];

        $output = $schema->mapColumnsToEventData($result);

        $this->assertSame(unserialize($result['metadata']), $output['metadata']);
        $this->assertSame(unserialize($result['payload']), $output['payload']);
    }
}
