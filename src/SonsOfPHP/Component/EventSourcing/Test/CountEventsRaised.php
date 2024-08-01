<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Test;

use PHPUnit\Framework\Constraint\Constraint;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;

/**
 * Usage:
 *   $this->assertThat($aggregate, new CountEventsRaised(0));
 *
 * @author joshua@sonsofphp.com
 */
final class CountEventsRaised extends Constraint
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(private readonly int $count) {}

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof AggregateInterface) {
            throw \LogicException(sprintf('Object must implement "%s"', AggregateInterface::class));
        }

        return $this->count === count($other->peekPendingEvents());
    }

    public function toString(): string
    {
        return sprintf('has raised "%d" events', $this->count);
    }
}
