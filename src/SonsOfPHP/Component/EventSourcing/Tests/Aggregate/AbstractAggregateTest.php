<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId
 * @uses \SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion
 */
final class AbstractAggregateTest extends TestCase
{
    /**
     * @covers ::hasPendingEvents
     */
    public function testItHasPendingEvents(): void
    {
        $aggregate = new FakeAggregate('id');
        $this->assertFalse($aggregate->hasPendingEvents());

        $message = $this->createMock(MessageInterface::class);
        $aggregate->raiseThisEvent($message);

        $this->assertTrue($aggregate->hasPendingEvents());
    }

    /**
     * @covers ::applyEvent
     * @covers ::raiseEvent
     */
    public function testRaiseEventWillApplyMetadata(): void
    {
        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())->method('withMetadata');

        $aggregate = new FakeAggregate('id');
        $aggregate->raiseThisEvent($message);
    }

    /**
     * @covers ::applyEvent
     * @covers ::getPendingEvents
     * @covers ::raiseEvent
     */
    public function testRaiseEventWillAddEventToPendingEvents(): void
    {
        $message = $this->createMock(MessageInterface::class);

        $aggregate = new FakeAggregate('id');
        $this->assertCount(0, $aggregate->getPendingEvents());

        $refObj = new \ReflectionObject($aggregate);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->setAccessible(true);
        $refMet->invoke($aggregate, $message);

        $this->assertCount(1, $aggregate->getPendingEvents());
    }

    /**
     * @covers ::buildFromEvents
     * @covers ::getAggregateId
     * @covers ::getAggregateVersion
     */
    public function testBuildFromEvents(): void
    {
        $events = function () {
            yield $this->createMock(MessageInterface::class);
        };

        $aggregate = FakeAggregate::buildFromEvents(new AggregateId('id'), $events());
        $this->assertSame('id', $aggregate->getAggregateId()->toString());
        $this->assertSame(1, $aggregate->getAggregateVersion()->toInt());
    }

    /**
     * @covers ::__construct
     */
    public function testItWillRaiseExceptionWithInvalidId(): void
    {
        $this->expectException(\TypeError::class);
        $this->getMockForAbstractClass(AbstractAggregate::class, [new \stdClass()]);
    }
}
