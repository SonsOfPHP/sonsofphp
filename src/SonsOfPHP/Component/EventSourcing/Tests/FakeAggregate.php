<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

class FakeAggregate extends AbstractAggregate
{
    public function raiseThisEvent(MessageInterface $event): void
    {
        $this->raiseEvent($event);
    }
}
