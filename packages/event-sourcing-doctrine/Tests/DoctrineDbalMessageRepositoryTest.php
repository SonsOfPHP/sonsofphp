<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Doctrine\EventSourcing\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Doctrine\EventSourcing\DoctrineDbalMessageRepository;
use SonsOfPHP\Bridge\Doctrine\EventSourcing\TableSchemaInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\SerializableMessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use SonsOfPHP\Component\EventSourcing\Tests\FakeSerializableMessage;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Doctrine\EventSourcing\DoctrineDbalMessageRepository
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 */
final class DoctrineDbalMessageRepositoryTest extends TestCase
{
    private Connection $connection;
    private MessageSerializerInterface $messageSerializer;
    private TableSchemaInterface $tableSchema;

    protected function setUp(): void
    {
        $this->connection = $this->createStub(Connection::class);
        $this->messageSerializer = $this->createMock(MessageSerializerInterface::class);
        $this->tableSchema = $this->createMock(TableSchemaInterface::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $this->assertInstanceOf(MessageRepositoryInterface::class, $repository);
    }

    /**
     * @covers ::persist
     */
    public function testPersist(): void
    {
        // @phpstan-ignore-next-line
        $this->connection
            ->expects($this->once())
            ->method('insert');

        // @phpstan-ignore-next-line
        $this->messageSerializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturn([
                'payload' => [],
                'metadata' => [
                    Metadata::EVENT_ID => 'event-id',
                    Metadata::EVENT_TYPE => 'event-type',
                    Metadata::TIMESTAMP => '2022-04-20',
                    Metadata::TIMESTAMP_FORMAT => 'Y-m-d',
                    Metadata::AGGREGATE_ID => 'aggregate-id',
                    Metadata::AGGREGATE_VERSION => 123,
                ],
            ]);

        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = $this->createMock(SerializableMessageInterface::class);
        $message
            ->expects($this->once())
            ->method('getAggregateId')
            ->willReturn(new AggregateId('unique-id'));
        $message
            ->expects($this->once())
            ->method('getAggregateVersion')
            ->willReturn(new AggregateVersion(100));

        $repository->persist($message);
    }

    /**
     * @covers ::persist
     */
    public function testPersistWillThrowExceptionWhenThereIsNoAggregateId(): void
    {
        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = FakeSerializableMessage::new();

        $this->expectException(EventSourcingException::class);
        $repository->persist($message);
    }

    /**
     * @covers ::persist
     */
    public function testPersistWillThrowExceptionWhenThereIsNoAggregateVersion(): void
    {
        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = FakeSerializableMessage::new();
        $this->expectException(EventSourcingException::class);
        $repository->persist($message);
    }

    /**
     * @covers ::persist
     */
    public function testPersistWillThrowExceptionWhenThereIsMissingMetadata(): void
    {
        // @phpstan-ignore-next-line
        $this->messageSerializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturn([
                'payload' => [],
                'metadata' => [],
            ]);

        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = $this->createMock(SerializableMessageInterface::class);
        $message
            ->expects($this->once())
            ->method('getAggregateId')
            ->willReturn(new AggregateId('id'));
        $message
            ->expects($this->once())
            ->method('getAggregateVersion')
            ->willReturn(new AggregateVersion(100));

        $this->expectException(EventSourcingException::class);
        $repository->persist($message);
    }

    /**
     * @covers ::find
     */
    public function testFindWithoutVersion(): void
    {
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getColumns')
            ->willReturn([
                'id' => Type::getType('string'),
                'aggregate_id' => Type::getType('string'),
                'aggregate_version' => Type::getType('integer'),
                'data' => Type::getType('json'),
            ]);
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getAggregateIdColumn')
            ->willReturn('aggregate_id');
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('mapColumnsToEventData')
            ->willReturn([
                'payload' => [],
                'metadata' => [],
            ]);

        $result = $this->createMock(Result::class);
        $result
            ->expects($this->once())
            ->method('iterateAssociative')
            ->willReturn(new \ArrayIterator([
                [
                    'id' => 'db-unique-id',
                    'aggregate_id' => 'unique-id',
                    'aggregate_version' => 100,
                    'data' => json_encode([]),
                ],
            ]));

        $builder = $this->createMock(QueryBuilder::class);
        $builder
            ->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        // @phpstan-ignore-next-line
        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder);

        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = $this->createMock(SerializableMessageInterface::class);
        // @phpstan-ignore-next-line
        $this->messageSerializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($message);

        $output = $repository->find(new AggregateId('unique-id'))->current();

        $this->assertSame($message, $output);
    }

    /**
     * @covers ::find
     */
    public function testFindWillThrowExceptionWhenAggregateNotFound(): void
    {
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getColumns')
            ->willReturn([
                'id' => Type::getType('string'),
                'aggregate_id' => Type::getType('string'),
                'aggregate_version' => Type::getType('integer'),
                'data' => Type::getType('json'),
            ]);
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getAggregateIdColumn')
            ->willReturn('aggregate_id');

        $result = $this->createMock(Result::class);
        $result
            ->expects($this->once())
            ->method('iterateAssociative')
            ->willReturn(new \ArrayIterator());

        $builder = $this->createMock(QueryBuilder::class);
        $builder
            ->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        // @phpstan-ignore-next-line
        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder);

        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $this->expectException(AggregateNotFoundException::class);
        $repository->find(new AggregateId('unique-id'))->current();
    }

    /**
     * @covers ::find
     */
    public function testFindWithVersion(): void
    {
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getColumns')
            ->willReturn([
                'id' => Type::getType('string'),
                'aggregate_id' => Type::getType('string'),
                'aggregate_version' => Type::getType('integer'),
                'data' => Type::getType('json'),
            ]);
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getAggregateIdColumn')
            ->willReturn('aggregate_id');
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('getAggregateVersionColumn')
            ->willReturn('aggregate_version');
        // @phpstan-ignore-next-line
        $this->tableSchema
            ->expects($this->once())
            ->method('mapColumnsToEventData')
            ->willReturn([
                'payload' => [],
                'metadata' => [],
            ]);

        $result = $this->createMock(Result::class);
        $result
            ->expects($this->once())
            ->method('iterateAssociative')
            ->willReturn(new \ArrayIterator([
                [
                    'id' => 'db-unique-id',
                    'aggregate_id' => 'unique-id',
                    'aggregate_version' => 100,
                    'data' => json_encode([]),
                ],
            ]));

        $builder = $this->createMock(QueryBuilder::class);
        $builder
            ->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        // @phpstan-ignore-next-line
        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($builder);

        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $message = $this->createMock(SerializableMessageInterface::class);
        // @phpstan-ignore-next-line
        $this->messageSerializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($message);

        $output = $repository->find(new AggregateId('unique-id'), new AggregateVersion(100))->current();

        $this->assertSame($message, $output);
    }
}
