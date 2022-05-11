<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Doctrine\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Bridge\Doctrine\DoctrineDbalMessageRepository;
use SonsOfPHP\Component\EventSourcing\Bridge\Doctrine\TableSchemaInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\Serializer\MessageSerializerInterface;

final class DoctrineDbalMessageRepositoryTest extends TestCase
{
    private Connection $connection;
    private MessageSerializerInterface $messageSerializer;
    private TableSchemaInterface $tableSchema;

    protected function setUp(): void
    {
        $this->connection        = $this->createStub(Connection::class);
        $this->messageSerializer = $this->createMock(MessageSerializerInterface::class);
        $this->tableSchema       = $this->createMock(TableSchemaInterface::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $repository = new DoctrineDbalMessageRepository(
            $this->connection,
            $this->messageSerializer,
            $this->tableSchema
        );

        $this->assertInstanceOf(MessageRepositoryInterface::class, $repository);
    }
}
