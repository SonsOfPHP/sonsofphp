<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use SonsOfPHP\Component\EventDispatcher\EventSubscriberInterface;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;
use stdClass;

#[CoversClass(ListenerProvider::class)]
#[UsesClass(ListenerProvider::class)]
final class ListenerProviderTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new ListenerProvider();
        $this->assertInstanceOf(ListenerProviderInterface::class, $provider); // @phpstan-ignore-line
    }

    public function testGetListenersForUnknownEventReturnsEmptyArray(): void
    {
        $provider  = new ListenerProvider();
        $event     = new stdClass();
        $listeners = $provider->getListenersForEvent($event);
        $this->assertEmpty($listeners);
    }

    public function testGetListenersForEvent(): void
    {
        $provider = new ListenerProvider();
        $provider->add('stdClass', function (): void {});

        $listeners = $provider->getListenersForEvent(new stdClass());
        $this->assertCount(1, $listeners);
    }

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

    public function testItCanAddSubscriber(): void
    {
        $subscriber = new class implements EventSubscriberInterface {
            public static function getSubscribedEvents(): Generator
            {
                yield 'event.one' => 'handle';
                yield 'event.two' => ['handle', 0];
                yield 'event.three' => ['handle'];
                yield 'event.four' => [['handle'], ['handle', 255]];
            }

            public function handle(): void {}
        };

        $provider = new ListenerProvider();

        $this->assertEmpty($provider->getListenersForEventName('event.one'));
        $this->assertEmpty($provider->getListenersForEventName('event.two'));
        $this->assertEmpty($provider->getListenersForEventName('event.three'));
        $this->assertEmpty($provider->getListenersForEventName('event.four'));
        $provider->addSubscriber($subscriber);
        $this->assertCount(1, $provider->getListenersForEventName('event.one'));
        $this->assertCount(1, $provider->getListenersForEventName('event.two'));
        $this->assertCount(1, $provider->getListenersForEventName('event.three'));
        $this->assertCount(2, $provider->getListenersForEventName('event.four'));
    }
}
