<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Metadata;
use Doctrine\DBAL\Schema\Schema;

/**
 * The idea behind a table schema is so we can have many different
 * table schemas. You can store everything in one table or split
 * everything up into multiple tables
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class TableSchema
{
    public function getTableName(): string
    {
        return 'event_store';
    }

    public function createTable(): void
    {
        $schema = new Schema();
        $this->configureSchema($schema);
    }

    public function configureSchema(Schema $schema): void
    {
        if ($schema->hasTable($this->getTableName())) {
            return;
        }

        $table = $schema->createTable($this->getTableName());
        $table->addColumn('event_id', 'string', ['length' => 255]);
        $table->addColumn('aggregate_id', 'string', ['length' => 255]);
        $table->addColumn('aggregate_version', 'integer', ['unsigned' => true]);
        $table->addColumn('event_type', 'string', ['length' => 255]);
        $table->addColumn('event_data', 'json');
        $table->addColumn('created_at', 'datetime_immutable');
        $table->setPrimaryKey(['event_id']);
        $table->addIndex(['aggregate_id']); // All Events
        $table->addIndex(['aggregate_id', 'aggregate_version']); // All Events by Version
    }

    public function mapEventDataToColumns(array $data): array
    {
        return [
            'event_id'          => $data['meta'][Metadata::EVENT_ID],
            'aggregate_id'      => $data['meta'][Metadata::AGGREGATE_ID],
            'aggregate_version' => $data['meta'][Metadata::AGGREGATE_VERSION],
            'event_type'        => $data['meta'][Metadata::EVENT_TYPE],
            'event_data'        => $data,
            'created_at'        => $data['meta'][Metadata::TIMESTAMP],
        ];
    }

    public function mapColumnsToEventData(array $result): array
    {
        return $result['event_data'];
    }
}
