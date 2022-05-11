<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Doctrine;

use SonsOfPHP\Component\EventSourcing\Metadata;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;

/**
 * V2 Table Schema
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class TableSchemaV2 implements TableSchemaInterface
{
    public function getTableName(): string
    {
        return 'event_store';
    }

    public function getAggregateIdColumn(): string
    {
        return 'aggregate_id';
    }

    public function getAggregateVersionColumn(): string
    {
        return 'aggregate_version';
    }

    /**
     * Getting the columns with the Types helps to map things when doing selects and
     * inserts
     */
    public function getColumns(): array
    {
        return [
            'event_id'          => Type::getType('guid'),
            'aggregate_id'      => Type::getType('guid'),
            'aggregate_version' => Type::getType('integer'),
            'event_data'        => Type::getType('json'),
            'created_at'        => Type::getType('datetime_immutable'),
            'event_type'        => Type::getType('string'),
        ];
    }

    /**
     * The input is the serialized data and the output is key value array
     * where the key is the column and the value is the data to insert
     * into that column
     */
    public function mapEventDataToColumns(array $data): array
    {
        return [
            'event_id'          => $data['metadata'][Metadata::EVENT_ID],
            'aggregate_id'      => $data['metadata'][Metadata::AGGREGATE_ID],
            'aggregate_version' => $data['metadata'][Metadata::AGGREGATE_VERSION],
            'event_data'        => $data,
            'created_at'        => new \DateTimeImmutable($data['metadata'][Metadata::TIMESTAMP]),
            'event_type'        => $data['metadata'][Metadata::EVENT_TYPE],
        ];
    }

    /**
     * The input is the result from the database query and the output
     * is the event data. After getting the data back from the database
     * it will be put through the MessageSerializer::deserialize for further
     * processing.
     */
    public function mapColumnsToEventData(array $result): array
    {
        return json_decode($result['event_data'], true);
    }
}
