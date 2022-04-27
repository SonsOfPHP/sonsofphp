<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Aggregate;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateTrait;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
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
}
