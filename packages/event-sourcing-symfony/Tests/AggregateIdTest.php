<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
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
