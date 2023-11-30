<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Test;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Test\CountEventsRaised;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Test\CountEventsRaised
 */
final class CountEventsRaisedTest extends TestCase
{
    private $aggregate;

    public function setUp(): void
    {
        $this->aggregate = $this->createMock(AggregateInterface::class);
    }

    /**
     * @covers ::matches
     */
    public function testMatchesWorksAsExpectedWhenNoEvents(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([]);

        $constraint = new CountEventsRaised(1);
        $method = new \ReflectionMethod($constraint, 'matches');

        $this->assertFalse($method->invoke($constraint, $this->aggregate));
    }

    /**
     * @covers ::matches
     */
    public function testMatchesWorksAsExpectedWhenEventsWereRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([
            new \stdClass(),
        ]);

        $constraint = new CountEventsRaised(1);
        $method = new \ReflectionMethod($constraint, 'matches');

        $this->assertTrue($method->invoke($constraint, $this->aggregate));
    }

    /**
     * @covers ::toString
     */
    public function testToStringReturnsCorrectMessage(): void
    {
        $constraint = new CountEventsRaised(123);

        $this->assertSame('has raised "123" events', $constraint->toString());
    }
}
