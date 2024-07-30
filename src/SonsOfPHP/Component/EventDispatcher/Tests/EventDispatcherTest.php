<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use SonsOfPHP\Component\EventDispatcher\AbstractStoppableEvent;
use SonsOfPHP\Component\EventDispatcher\EventDispatcher;
use SonsOfPHP\Component\EventDispatcher\EventSubscriberInterface;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;
use stdClass;

/**
 *
 * @uses \SonsOfPHP\Component\EventDispatcher\EventDispatcher
 * @uses \SonsOfPHP\Component\EventDispatcher\ListenerProvider
 * @uses \SonsOfPHP\Component\EventDispatcher\StoppableEventTrait
 * @coversNothing
 */
#[CoversClass(EventDispatcher::class)]
final class EventDispatcherTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $dispatcher = new EventDispatcher();

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher); // @phpstan-ignore-line
    }

    public function testDispatch(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('stdClass', function ($event): void {});

        $event = new stdClass();
        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    public function testDispatchWithStoppedEvent(): void
    {
        $event = new class () extends AbstractStoppableEvent {};

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener($event, function ($event): never {
            throw new RuntimeException('This should never run');
        });

        $event->stopPropagation();

        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    public function testItWillReturnTheSameEventWhenNoListeners(): void
    {
        $dispatcher = new EventDispatcher();

        $event = new stdClass();
        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    public function testItCanAddEventListener(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('add');

        $dispatcher = new EventDispatcher($provider);

        $dispatcher->addListener('stdClass', function (): void {});
    }

    public function testAddListenerWithObject(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('add')->with($this->identicalTo('stdClass'));

        $dispatcher = new EventDispatcher($provider);

        $dispatcher->addListener(new stdClass(), function (): void {});
    }

    public function testItCanAddEventSubscriber(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('addSubscriber');

        $dispatcher = new EventDispatcher($provider);

        $subscriber = $this->createMock(EventSubscriberInterface::class);

        $dispatcher->addSubscriber($subscriber);
    }
}
