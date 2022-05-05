<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateTrait;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Generator;

class FakeAggregate implements AggregateInterface
{
    use AggregateTrait {
        raiseEvent as public;
        applyEvent as public;
    }

    private function __construct()
    {
    }
}
