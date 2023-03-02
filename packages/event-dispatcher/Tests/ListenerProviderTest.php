<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventDispatcher\ListenerProvider
 *
 * @internal
 */
final class ListenerProviderTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $provider); // @phpstan-ignore-line
    }

    /**
     * @covers ::getListenersForEvent
     */
    public function testGetListenersForUnknownEventReturnsEmptyArray(): void
    {
        $provider  = new ListenerProvider();
        $event     = new \stdClass();
        $listeners = $provider->getListenersForEvent($event);
        $this->assertCount(0, $listeners);
    }

    /**
     * @covers ::add
     * @covers ::getListenersForEvent
     */
    public function testGetListenersForEvent(): void
    {
        $provider = new ListenerProvider();
        $provider->add('stdClass', 'example listener object');

        $event     = new \stdClass();
        $listeners = $provider->getListenersForEvent($event);
        $this->assertCount(1, $listeners);
    }
}
