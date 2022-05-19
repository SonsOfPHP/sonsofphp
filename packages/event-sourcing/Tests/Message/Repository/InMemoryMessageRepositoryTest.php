<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Message\Repository;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\InMemoryMessageRepository;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Message\Repository\InMemoryMessageRepository
 */
final class InMemoryMessageRepositoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $repository = new InMemoryMessageRepository();
        $this->assertInstanceOf(MessageRepositoryInterface::class, $repository);
    }

    /**
     * @covers ::persist
     * @covers ::find
     */
    public function testPersistAndFind(): void
    {
        $repository = new InMemoryMessageRepository();

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->any())->method('getAggregateId')->willReturn(AggregateId::fromString('unique-id'));
        $message->expects($this->any())->method('getAggregateVersion')->willReturn(AggregateVersion::zero());

        $repository->persist($message);

        $result = $repository->find(AggregateId::fromString('unique-id'));
        $this->assertSame($message, $result->current());
    }

    /**
     * @covers ::find
     */
    public function testFindWhenAggregateIdIsNotFound(): void
    {
        $repository = new InMemoryMessageRepository();

        $this->expectException(AggregateNotFoundException::class);
        $repository->find(AggregateId::fromString('hit'))->current();
    }

    /**
     * @covers ::find
     */
    public function testFindWithVersion(): void
    {
        $repository = new InMemoryMessageRepository();

        $message1 = $this->createMock(MessageInterface::class);
        $message1->expects($this->any())->method('getAggregateId')->willReturn(AggregateId::fromString('unique-id'));
        $message1->expects($this->any())->method('getAggregateVersion')->willReturn(AggregateVersion::fromInt(1));
        $repository->persist($message1);

        $message2 = $this->createMock(MessageInterface::class);
        $message2->expects($this->any())->method('getAggregateId')->willReturn(AggregateId::fromString('unique-id'));
        $message2->expects($this->any())->method('getAggregateVersion')->willReturn(AggregateVersion::fromInt(2));
        $repository->persist($message2);

        $result = $repository->find(AggregateId::fromString('unique-id'), AggregateVersion::fromInt(1));
        $this->assertSame($message2, $result->current());
    }
}
