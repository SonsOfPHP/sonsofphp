<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate;

/**
 * Abstract Aggregate ID
 *
 * This class is used for when you want to extend it and create your own
 * class like a "UserAggregateId"
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractAggregateId implements AggregateIdInterface
{
    use AggregateIdTrait;
}
