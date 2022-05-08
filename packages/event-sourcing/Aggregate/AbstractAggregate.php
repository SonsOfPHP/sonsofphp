<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAggregate implements AggregateInterface
{
    use AggregateTrait;
}
