<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use Symfony\Component\Uid\Uuid;

#[CoversClass(AggregateId::class)]
#[UsesClass(AbstractAggregateId::class)]
final class AggregateIdTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $id = new AggregateId();

        $this->assertInstanceOf(AggregateIdInterface::class, $id);
    }

    public function testItGeneratesValidUuidWhenNoArgument(): void
    {
        $id = new AggregateId();

        $this->assertTrue(Uuid::isValid($id->toString()));
    }

    public function testItWillNotAutogenerateWhenValuePassedIn(): void
    {
        $id = new AggregateId('example-id');

        $this->assertSame('example-id', $id->toString());
    }
}
