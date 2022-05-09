<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;
use PHPUnit\Framework\TestCase;

final class AbstractAggregateTest extends TestCase
{
    public function testNewStaticWithAggregateId(): void
    {
        $abstract = $this->getMockForAbstractClass(AbstractAggregate::class, ['id']);

        $aggregate = $abstract::new(AggregateId::fromString('id'));
        $this->assertInstanceOf(AggregateIdInterface::class, $aggregate->getAggregateId());
        $this->assertInstanceOf(AggregateVersionInterface::class, $aggregate->getAggregateVersion());

        $this->assertSame('id', $aggregate->getAggregateId()->toString());
        $this->assertSame(0, $aggregate->getAggregateVersion()->toInt());
    }

    public function testItHasPendingEvents()
    {
        $abstract = $this->getMockForAbstractClass(AbstractAggregate::class, ['id']);
        $this->assertFalse($abstract->hasPendingEvents());

        $message = $this->createMock(MessageInterface::class);
        $refObj = new \ReflectionObject($abstract);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->invoke($abstract, $message);

        $this->assertTrue($abstract->hasPendingEvents());
    }

    public function testRaiseEventWillApplyMetadata(): void
    {
        $abstract = $this->getMockForAbstractClass(AbstractAggregate::class, ['id']);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())->method('withMetadata');

        $aggregate = $abstract::new(AggregateId::fromString('id'));
        $refObj = new \ReflectionObject($aggregate);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->invoke($aggregate, $message);
    }

    public function testRaiseEventWillAddEventToPendingEvents(): void
    {
        $abstract = $this->getMockForAbstractClass(AbstractAggregate::class, ['id']);
        $message = $this->createMock(MessageInterface::class);

        $aggregate = $abstract::new(AggregateId::fromString('id'));
        $this->assertCount(0, $aggregate->getPendingEvents());

        $refObj = new \ReflectionObject($aggregate);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->invoke($aggregate, $message);

        $this->assertCount(1, $aggregate->getPendingEvents());
    }

    public function testBuildFromEvents(): void
    {
        $abstract = $this->getMockForAbstractClass(AbstractAggregate::class, ['id']);

        $events = function () {
            yield $this->createMock(MessageInterface::class);
        };

        $aggregate = FakeAggregate::buildFromEvents(new AggregateId('id'), $events());
        $this->assertSame('id', $aggregate->getAggregateId()->toString());
        $this->assertSame(1, $aggregate->getAggregateVersion()->toInt());
    }

    public function testItWillRaiseExceptionWithInvalidId(): void
    {
        $this->expectException(EventSourcingException::class);
        $this->getMockForAbstractClass(AbstractAggregate::class, [123]);
    }
}
