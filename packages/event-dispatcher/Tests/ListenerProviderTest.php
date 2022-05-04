<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use SonsOfPHP\Component\EventDispatcher\ListenerProvider;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ListenerProviderTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $provider);
    }

    public function testGetListenersForUnknownEventReturnsEmptyArray(): void
    {
        $provider = new ListenerProvider();
        $event = new \stdClass();
        $listeners = $provider->getListenersForEvent($event);
        $this->assertCount(0, $listeners);
    }

    public function testGetListenersForEvent(): void
    {
        $provider = new ListenerProvider();
        $provider->add('stdClass', 'example listener object');

        $event = new \stdClass();
        $listeners = $provider->getListenersForEvent($event);
        $this->assertCount(1, $listeners);
    }
}
