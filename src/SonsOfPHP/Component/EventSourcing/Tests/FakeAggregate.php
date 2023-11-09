<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Tests;

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Mapping\AsAggregate;
use SonsOfPHP\Component\EventSourcing\Mapping\AggregateId;
use SonsOfPHP\Component\EventSourcing\Mapping\ApplyEvent;

#[AsAggregate]
class FakeAggregate extends AbstractAggregate
{
    #[AggregateId]
    public $id;

    public function raiseThisEvent($event): void
    {
        $this->raiseEvent($event);
    }

    #[ApplyEvent('stdClass')]
    protected function applyExampleEvent($event) {}
}
