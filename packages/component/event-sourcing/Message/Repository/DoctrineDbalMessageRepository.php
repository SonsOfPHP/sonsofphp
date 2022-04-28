<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Repository\TableSchema\TableSchemaInterface;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;
use Generator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DoctrineDbalMessageRepository implements MessageRepositoryInterface
{
    private Connection $connection;
    private MessageSerializerInterface $serializer;
    private TableSchemaInterface $tableSchema;

    /**
     */
    public function __construct(Connection $connection, MessageSerializerInterface $serializer, TableSchemaInterface $tableSchema)
    {
        $this->connection  = $connection;
        $this->serializer  = $serializer;
        $this->tableSchema = $tableSchema;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(MessageInterface $message): void
    {
        $id      = $message->getAggregateId();
        $version = $message->getAggregateVersion();

        if (null === $id || null === $version) {
            throw new EventSourcingException('No ID or Verion');
        }

        $data = $this->serializer->serialize($message);

        $requiredMetadata = [
            Metadata::EVENT_ID,
            Metadata::EVENT_TYPE,
            Metadata::AGGREGATE_ID,
            Metadata::AGGREGATE_VERSION,
            Metadata::TIMESTAMP,
            Metadata::TIMESTAMP_FORMAT,
        ];

        if (count($requiredMetadata) != count(array_intersect_key(array_flip($requiredMetadata), $data['metadata']))) {
            throw new EventSourcingException('metadata is missing one or more required values');
        }

        // returns int|string The number of aggected rows
        $this->connection->insert(
            $this->tableSchema->getTableName(),
            $this->tableSchema->mapEventDataToColumns($data),
            array_values($this->tableSchema->getColumns())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function find(AggregateIdInterface $id, ?AggregateVersionInterface $version = null): Generator
    {
        $columnsWithTypes       = $this->tableSchema->getColumns();
        $aggregateIdColumn      = $this->tableSchema->getAggregateIdColumn();
        $aggregateVersionColumn = $this->tableSchema->getAggregateVersionColumn();

        $builder = $this->connection->createQueryBuilder();
        $builder->select(array_keys($columnsWithTypes));
        $builder->from($this->tableSchema->getTableName());
        $builder->where(sprintf('%s = :aggregate_id', $aggregateIdColumn));
        $builder->orderBy($aggregateVersionColumn, 'ASC');
        $builder->setParameter('aggregate_id', $id->toString(), $columnsWithTypes[$aggregateIdColumn]);

        if ($version) {
            $builder->andWhere(sprintf('%s > :aggregate_version', $aggregateVersionColumn));
            $builder->setParameter('aggregate_version', $version->toInt(), $columnsWithTypes[$aggregateVersionColumn]);
        }

        $results = $builder->executeQuery()->iterateAssociative(); // Generator

        foreach ($results as $result) {
            $data    = $this->tableSchema->mapColumnsToEventData($result);
            $message = $this->serializer->deserialize($data);

            yield $message;
        }
    }
}
