<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Test;

use PHPUnit\Framework\Constraint\Constraint;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;

/**
 * Usage:
 *   $this->assertThat($aggregate, new EventRaised(Event::class));
 *
 * @author joshua@sonsofphp.com
 */
final class EventRaised extends Constraint
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(private readonly string $eventClass) {}

    /**
     * AggregateInterface $other
     */
    protected function matches(mixed $other): bool
    {
        if (!$other instanceof AggregateInterface) {
            throw \LogicException(sprintf('Object must implement "%s"', AggregateInterface::class));
        }

        foreach ($other->peekPendingEvents() as $event) {
            if ($event instanceof $this->eventClass) {
                return true;
            }
        }

        return false;
    }

    public function toString(): string
    {
        return sprintf('has raised the event "%s"', $this->eventClass);
    }
}
