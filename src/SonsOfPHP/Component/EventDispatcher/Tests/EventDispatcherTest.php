<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use SonsOfPHP\Component\EventDispatcher\EventDispatcher;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher);
    }

    public function testDispatchReturnsEvent(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertNotEmpty($dispatcher->dispatch(new \stdClass()));
    }

    public function testDispatchReturnsSameEventThatWasDispatched(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $event  = new \stdClass();
        $return = $dispatcher->dispatch($event);
        $this->assertSame($event, $return);
    }
}
