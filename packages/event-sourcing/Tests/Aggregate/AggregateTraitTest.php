<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateTrait;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Message\AbstractMessage;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Tests\FakeAggregate;
use PHPUnit\Framework\TestCase;

final class AggregateTraitTest extends TestCase
{
    public function testNewStaticFunction(): void
    {
        $trait = $this->getMockForTrait(AggregateTrait::class);

        $aggregate = $trait::new(AggregateId::fromString('id'));
        $this->assertInstanceOf(AggregateIdInterface::class, $aggregate->getAggregateId());
        $this->assertInstanceOf(AggregateVersionInterface::class, $aggregate->getAggregateVersion());

        $this->assertSame('id', $aggregate->getAggregateId()->toString());
        $this->assertSame(0, $aggregate->getAggregateVersion()->toInt());
    }

    public function testRaiseEventWillApplyMetadata(): void
    {
        $trait = $this->getMockForTrait(AggregateTrait::class);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())->method('withMetadata');

        $aggregate = $trait::new(AggregateId::fromString('id'));
        $refObj = new \ReflectionObject($aggregate);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->invoke($aggregate, $message);
    }

    public function testRaiseEventWillAddEventToPendingEvents(): void
    {
        $trait = $this->getMockForTrait(AggregateTrait::class);
        $message = $this->createMock(MessageInterface::class);

        $aggregate = $trait::new(AggregateId::fromString('id'));
        $this->assertCount(0, $aggregate->getPendingEvents());

        $refObj = new \ReflectionObject($aggregate);
        $refMet = $refObj->getMethod('raiseEvent');
        $refMet->invoke($aggregate, $message);

        $this->assertCount(1, $aggregate->getPendingEvents());
    }

    public function testBuildFromEvents(): void
    {
        $trait = $this->getMockForTrait(AggregateTrait::class);

        $events = function () {
            yield $this->createMock(MessageInterface::class);
        };

        $aggregate = FakeAggregate::buildFromEvents(new AggregateId('id'), $events());
        $this->assertSame('id', $aggregate->getAggregateId()->toString());
        $this->assertSame(1, $aggregate->getAggregateVersion()->toInt());
    }
}
