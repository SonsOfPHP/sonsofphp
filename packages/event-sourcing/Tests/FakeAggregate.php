<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Generator;

class FakeAggregate extends AbstractAggregate
{
    public function raiseThisEvent($event)
    {
        $this->raiseEvent($event);
    }
}
