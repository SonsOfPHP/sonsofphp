<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate\Repository;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepository;
use SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\Repository\InMemoryMessageRepository;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;

class Msg extends AbstractMessage {}

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepository
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepository
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Repository\InMemoryMessageRepository
 * @uses \SonsOfPHP\Component\EventSourcing\Message\AbstractMessage
 * @uses \SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\NullMessageEnricherProvider
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessageMetadata
 * @uses \SonsOfPHP\Component\EventSourcing\Message\MessagePayload
 */
final class AggregateRepositoryTest extends TestCase
{
    private string $aggregateClass;
    private EventDispatcherInterface $eventDispatcher;
    private MessageRepositoryInterface $messageRepository;

    protected function setUp(): void
    {
        $this->aggregateClass    = FakeAggregate::class;
        $this->eventDispatcher   = $this->createMock(EventDispatcherInterface::class);
        $this->messageRepository = new InMemoryMessageRepository();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );
        $this->assertInstanceOf(AggregateRepositoryInterface::class, $repository);
    }

    /**
     * @covers ::persist
     */
    public function testPersistWillUseEventDispatcher(): void
    {
        $this->eventDispatcher->expects($this->once())->method('dispatch'); // @phpstan-ignore-line
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );

        $aggregate = new FakeAggregate('unique-id');

        $message = Msg::new();
        $aggregate->raiseThisEvent($message);

        $repository->persist($aggregate);
    }

    /**
     * @covers ::find
     * @covers ::persist
     */
    public function testPersistAndFind(): void
    {
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );

        $aggregate = new FakeAggregate('unique-id');

        $message = Msg::new();
        $aggregate->raiseThisEvent($message);

        $repository->persist($aggregate);

        $result = $repository->find(AggregateId::fromString('unique-id'));
        $this->assertTrue($aggregate->getAggregateId()->equals($result->getAggregateId()));
    }

    /**
     * @covers ::find
     * @covers ::persist
     */
    public function testPersistAndFindWithoutUsingAggregateId(): void
    {
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );

        $aggregate = new FakeAggregate('unique-id');

        $message = Msg::new();
        $aggregate->raiseThisEvent($message);

        $repository->persist($aggregate);

        $result = $repository->find('unique-id');
        $this->assertTrue($aggregate->getAggregateId()->equals($result->getAggregateId()));
    }

    /**
     * @covers ::find
     */
    public function testItThrowsExceptionWhenPassingInIncorrectArgumentType(): void
    {
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );

        $this->expectException(\TypeError::class);
        $result = $repository->find(123);
    }

    /**
     * @covers ::find
     */
    public function testItReturnsNullWhenAggregateNotFound(): void
    {
        $repository = new AggregateRepository(
            $this->aggregateClass,
            $this->eventDispatcher,
            $this->messageRepository
        );

        $this->assertNull($repository->find('unique-id'));
    }
}
