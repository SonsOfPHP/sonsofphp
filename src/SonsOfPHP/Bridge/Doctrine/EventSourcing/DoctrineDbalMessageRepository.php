<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\EventSourcing;

use Doctrine\DBAL\Connection;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class DoctrineDbalMessageRepository implements MessageRepositoryInterface
{
    public function __construct(private readonly Connection $connection, private readonly MessageSerializerInterface $serializer, private readonly TableSchemaInterface $tableSchema) {}

    public function persist(MessageInterface $message): void
    {
        $id      = $message->getAggregateId();
        $version = $message->getAggregateVersion();

        if (!$id instanceof AggregateIdInterface || !$version instanceof AggregateVersionInterface) {
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

        if (\count($requiredMetadata) != \count(array_intersect_key(array_flip($requiredMetadata), $data['metadata']))) {
            throw new EventSourcingException('metadata is missing one or more required values');
        }

        // returns int|string The number of aggected rows
        $this->connection->insert(
            $this->tableSchema->getTableName(),
            $this->tableSchema->mapEventDataToColumns($data),
            array_values($this->tableSchema->getColumns())
        );
    }

    public function find(string|AggregateIdInterface $id, int|AggregateVersionInterface $version = null): iterable
    {
        if (!$id instanceof AggregateIdInterface) {
            $id = new AggregateId($id);
        }

        if (\is_int($version)) {
            $version = new AggregateVersion($version);
        }

        $columnsWithTypes       = $this->tableSchema->getColumns();
        $aggregateIdColumn      = $this->tableSchema->getAggregateIdColumn();
        $aggregateVersionColumn = $this->tableSchema->getAggregateVersionColumn();

        $builder = $this->connection->createQueryBuilder();
        $builder->select(array_keys($columnsWithTypes));
        $builder->from($this->tableSchema->getTableName());
        $builder->where(sprintf('%s = :aggregate_id', $aggregateIdColumn));
        $builder->orderBy($aggregateVersionColumn, 'ASC');
        $builder->setParameter('aggregate_id', $id->toString(), $columnsWithTypes[$aggregateIdColumn]);

        if ($version instanceof AggregateVersionInterface) {
            $builder->andWhere(sprintf('%s > :aggregate_version', $aggregateVersionColumn));
            $builder->setParameter('aggregate_version', $version->toInt(), $columnsWithTypes[$aggregateVersionColumn]);
        }

        $results = $builder->executeQuery()->iterateAssociative(); // Generator

        $resultCount = 0;
        foreach ($results as $result) {
            ++$resultCount;
            $data    = $this->tableSchema->mapColumnsToEventData($result);
            $message = $this->serializer->deserialize($data);

            yield $message;
        }

        if (0 === $resultCount) {
            throw new AggregateNotFoundException(sprintf('Aggregate "%s" could not be found', $id->toString()));
        }
    }
}
