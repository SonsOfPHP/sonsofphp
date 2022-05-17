<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventDispatcher\EventDispatcher;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;

final class EventDispatcherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher); // @phpstan-ignore-line
    }

    public function testDispatchReturnsEvent(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertNotEmpty($dispatcher->dispatch(new \stdClass()));
    }

    public function testDispatchReturnsSameEventThatWasDispatched(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $event = new \stdClass();
        $return = $dispatcher->dispatch($event);
        $this->assertSame($event, $return);
    }
}
