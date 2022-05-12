<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Bridge\Symfony\AggregateId;
use Symfony\Component\Uid\Uuid;

final class AggregateIdTest extends TestCase
{
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
