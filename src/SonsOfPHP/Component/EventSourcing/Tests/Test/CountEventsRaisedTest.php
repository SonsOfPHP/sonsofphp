<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Test\CountEventsRaised;
use stdClass;

/**
 * @uses \SonsOfPHP\Component\EventSourcing\Test\CountEventsRaised
 * @coversNothing
 */
#[CoversClass(CountEventsRaised::class)]
final class CountEventsRaisedTest extends TestCase
{
    private MockObject $aggregate;

    public function setUp(): void
    {
        $this->aggregate = $this->createMock(AggregateInterface::class);
    }

    public function testMatchesWorksAsExpectedWhenNoEvents(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([]);

        $constraint = new CountEventsRaised(1);
        $method = new ReflectionMethod($constraint, 'matches');

        $this->assertFalse($method->invoke($constraint, $this->aggregate));
    }

    public function testMatchesWorksAsExpectedWhenEventsWereRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([
            new stdClass(),
        ]);

        $constraint = new CountEventsRaised(1);
        $method = new ReflectionMethod($constraint, 'matches');

        $this->assertTrue($method->invoke($constraint, $this->aggregate));
    }

    public function testToStringReturnsCorrectMessage(): void
    {
        $constraint = new CountEventsRaised(123);

        $this->assertSame('has raised "123" events', $constraint->toString());
    }
}
