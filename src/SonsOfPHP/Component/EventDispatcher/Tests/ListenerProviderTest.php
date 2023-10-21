<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;
use SonsOfPHP\Component\EventDispatcher\EventSubscriberInterface;

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
     * @covers ::getListenersForEventName
     * @covers ::sortListeners
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
     * @covers ::getListenersForEventName
     * @covers ::sortListeners
     */
    public function testGetListenersForEvent(): void
    {
        $provider = new ListenerProvider();
        $provider->add('stdClass', function (): void {});

        $listeners = $provider->getListenersForEvent(new \stdClass());
        $this->assertCount(1, $listeners);
    }

    /**
     * @covers ::add
     * @covers ::sortListeners
     */
    public function testItCanPrioritizeListeners(): void
    {
        $provider = new ListenerProvider();
        $provider->add('event.name', function (): void { echo '2'; });
        $provider->add('event.name', function (): void { echo '3'; }, 1);
        $provider->add('event.name', function (): void { echo '1'; }, -1);

        ob_start();
        foreach ($provider->getListenersForEventName('event.name') as $listener) {
            $listener();
        }
        $this->assertSame('123', ob_get_clean());
    }

    /**
     * @covers ::addSubscriber
     */
    public function testItCanAddSubscriber(): void
    {
        $subscriber = new class () implements EventSubscriberInterface {
            public static function getSubscribedEvents()
            {
                yield 'event.one' => 'handle';
                yield 'event.two' => ['handle', 0];
                yield 'event.three' => ['handle'];
                yield 'event.four' => [['handle'], ['handle', 255]];
            }

            public function handle(): void {}
        };

        $provider = new ListenerProvider();

        $this->assertCount(0, $provider->getListenersForEventName('event.one'));
        $this->assertCount(0, $provider->getListenersForEventName('event.two'));
        $this->assertCount(0, $provider->getListenersForEventName('event.three'));
        $this->assertCount(0, $provider->getListenersForEventName('event.four'));
        $provider->addSubscriber($subscriber);
        $this->assertCount(1, $provider->getListenersForEventName('event.one'));
        $this->assertCount(1, $provider->getListenersForEventName('event.two'));
        $this->assertCount(1, $provider->getListenersForEventName('event.three'));
        $this->assertCount(2, $provider->getListenersForEventName('event.four'));
    }
}
