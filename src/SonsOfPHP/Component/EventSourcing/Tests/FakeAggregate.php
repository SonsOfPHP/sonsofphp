<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;

class FakeAggregate extends AbstractAggregate
{
    public function raiseThisEvent($event): void
    {
        $this->raiseEvent($event);
    }
}
