<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Test;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Usage:
 *   $this->assertThat($aggregate, new CountEventsRaised(0));
 *
 * @author joshua@sonsofphp.com
 */
final class CountEventsRaised extends Constraint
{
    public function __construct(private int $count) {}

    /**
     * AggregateInterface $other
     */
    protected function matches(mixed $other): bool
    {
        return $count === count($other->peekPendingEvents());
    }

    public function toString(): string
    {
        return sprintf('has raised "%d" events', $this->count);
    }
}
