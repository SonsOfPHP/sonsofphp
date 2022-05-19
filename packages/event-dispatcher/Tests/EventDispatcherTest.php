<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventDispatcher\EventDispatcher;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventDispatcher\EventDispatcher
 */
final class EventDispatcherTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher); // @phpstan-ignore-line
    }

    /**
     * @covers ::__construct
     * @covers ::dispatch
     */
    public function testDispatchReturnsEvent(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $this->assertNotEmpty($dispatcher->dispatch(new \stdClass()));
    }

    /**
     * @covers ::__construct
     * @covers ::dispatch
     */
    public function testDispatchReturnsSameEventThatWasDispatched(): void
    {
        $dispatcher = new EventDispatcher(new ListenerProvider());

        $event = new \stdClass();
        $return = $dispatcher->dispatch($event);
        $this->assertSame($event, $return);
    }
}
