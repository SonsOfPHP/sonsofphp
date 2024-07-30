<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\EventSourcing;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * V1 Table Schema.
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class TableSchemaV1 implements TableSchemaInterface
{
    public function getTableName(): string
    {
        return 'event_store';
    }

    public function getAggregateIdColumn(): string
    {
        return 'aggregate_root_id';
    }

    public function getAggregateVersionColumn(): string
    {
        return 'aggregate_root_version';
    }

    // public function createTable(): void
    // {
    //    $schema = new Schema();
    //    $this->configureSchema($schema);

    //    //foreach ($schema->toSql($this->connection->getDatabasePlatform()) as $sql) {
    //    //    $this->connection->executeStatement($sql);
    //    //}
    // }

    // public function configureSchema(Schema $schema): void
    // {
    //    if ($schema->hasTable($this->getTableName())) {
    //        return;
    //    }

    //    $table = $schema->createTable($this->getTableName());
    //    $table->addColumn('event_id', 'string', ['length' => 255]);
    //    $table->addColumn('aggregate_id', 'string', ['length' => 255]);
    //    $table->addColumn('aggregate_version', 'integer', ['unsigned' => true]);
    //    $table->addColumn('event_type', 'string', ['length' => 255]);
    //    $table->addColumn('event_data', 'json');
    //    $table->setPrimaryKey(['event_id']);
    //    $table->addIndex(['aggregate_id']); // All Events
    //    $table->addIndex(['aggregate_id', 'aggregate_version']); // All Events with Version
    // }

    /**
     * Getting the columns with the Types helps to map things when doing selects and
     * inserts.
     */
    public function getColumns(): array
    {
        return [
            'event_id'               => Type::getType('string'),
            'event_type'             => Type::getType('string'),
            'aggregate_root_id'      => Type::getType('string'),
            'aggregate_root_version' => Type::getType('integer'),
            'created_at'             => Type::getType('datetime'),
            'payload'                => Type::getType('array'),
            'metadata'               => Type::getType('array'),
        ];
    }

    /**
     * The input is the serialized data and the output is key value array
     * where the key is the column and the value is the data to insert
     * into that column.
     */
    public function mapEventDataToColumns(array $data): array
    {
        return [
            'event_id'               => $data['metadata'][Metadata::EVENT_ID],
            'event_type'             => $data['metadata'][Metadata::EVENT_TYPE],
            'aggregate_root_id'      => $data['metadata'][Metadata::AGGREGATE_ID],
            'aggregate_root_version' => $data['metadata'][Metadata::AGGREGATE_VERSION],
            'created_at'             => new DateTimeImmutable($data['metadata'][Metadata::TIMESTAMP]),
            'payload'                => $data['payload'],
            'metadata'               => $data['metadata'],
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
        return [
            'payload'  => unserialize($result['payload']),
            'metadata' => unserialize($result['metadata']),
        ];
    }
}
