<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Test;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;

/**
 * @author joshua@sonsofphp.com
 */
trait AggregateAssertionsTrait
{
    final public function assertEventRaised(string $eventClass, AggregateInterface $aggregate, string $message): void
    {
        $constraint = new EventRaised($eventClass);

        \PHPUnit\Framework\Assert::assertThat($aggregate, $constraint, $message);
    }

    final public function assertCountEventsRaised(int $count, AggregateInterface $aggregate, string $message): void
    {
        $constraint = new CountEventsRaised($count);

        \PHPUnit\Framework\Assert::assertThat($aggregate, $constraint, $message);
    }
}
