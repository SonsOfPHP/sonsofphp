<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests\Test;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Test\EventRaised;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventSourcing\Test\EventRaised
 *
 * @uses \SonsOfPHP\Component\EventSourcing\Test\EventRaised
 */
final class EventRaisedTest extends TestCase
{
    private $aggregate;

    public function setUp(): void
    {
        $this->aggregate = $this->createMock(AggregateInterface::class);
    }

    /**
     * @covers ::matches
     */
    public function testMatchesWorksAsExpectedWhenEventWasNotRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([]);

        $constraint = new EventRaised('stdClass');
        $method = new \ReflectionMethod($constraint, 'matches');

        $this->assertFalse($method->invoke($constraint, $this->aggregate));
    }

    /**
     * @covers ::matches
     */
    public function testMatchesWorksAsExpectedWhenEventWasRaised(): void
    {
        $this->aggregate->method('peekPendingEvents')->willReturn([
            new \stdClass(),
        ]);

        $constraint = new EventRaised('stdClass');
        $method = new \ReflectionMethod($constraint, 'matches');

        $this->assertTrue($method->invoke($constraint, $this->aggregate));
    }

    /**
     * @covers ::toString
     */
    public function testToStringReturnsCorrectMessage(): void
    {
        $constraint = new EventRaised('stdClass');

        $this->assertSame('has raised the event "stdClass"', $constraint->toString());
    }
}
