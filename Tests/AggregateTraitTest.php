<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\AggregateId;
use SonsOfPHP\Component\EventSourcing\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\AggregateTrait;
use SonsOfPHP\Component\EventSourcing\AggregateVersionInterface;
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
