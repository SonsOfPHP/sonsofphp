<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Generator;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class DoctrineDbalMessageRepository implements MessageRepositoryInterface
{
    private Connection $connection;
    private MessageSerializerInterface $serializer;

    /**
     */
    public function __construct(Connection $connection, MessageSerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
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
            $this->tableSchema->mapEventDataToColumns($data)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function find(AggregateIdInterface $id, ?AggregateVersionInterface $version = null): Generator
    {
        // @todo
    }
}
