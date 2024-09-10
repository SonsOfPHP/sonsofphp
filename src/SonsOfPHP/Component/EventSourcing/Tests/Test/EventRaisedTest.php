<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Test\EventRaised;
use stdClass;

/**
 * @uses \SonsOfPHP\Component\EventSourcing\Test\EventRaised
 * @coversNothing
 */
#[CoversClass(EventRaised::class)]
final class EventRaisedTest extends TestCase
{
    private MockObject $aggregate;

    protected function setUp(): void
    {
        $this->aggregate = $this->createMock(AggregateInterface::class);
    }

    public function testMatchesWorksAsExpectedWhenEventWasNotRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([]);

        $constraint = new EventRaised('stdClass');
        $method = new ReflectionMethod($constraint, 'matches');

        $this->assertFalse($method->invoke($constraint, $this->aggregate));
    }

    public function testMatchesWorksAsExpectedWhenEventWasRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([
            new stdClass(),
        ]);

        $constraint = new EventRaised('stdClass');
        $method = new ReflectionMethod($constraint, 'matches');

        $this->assertTrue($method->invoke($constraint, $this->aggregate));
    }

    public function testToStringReturnsCorrectMessage(): void
    {
        $constraint = new EventRaised('stdClass');

        $this->assertSame('has raised the event "stdClass"', $constraint->toString());
    }
}
