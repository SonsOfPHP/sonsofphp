<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventDispatcher\AbstractStoppableEvent;
use SonsOfPHP\Component\EventDispatcher\EventDispatcher;
use SonsOfPHP\Component\EventDispatcher\EventSubscriberInterface;
use SonsOfPHP\Component\EventDispatcher\ListenerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\EventDispatcher\EventDispatcher
 *
 * @uses \SonsOfPHP\Component\EventDispatcher\EventDispatcher
 * @uses \SonsOfPHP\Component\EventDispatcher\ListenerProvider
 * @uses \SonsOfPHP\Component\EventDispatcher\StoppableEventTrait
 */
final class EventDispatcherTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $dispatcher = new EventDispatcher();

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher); // @phpstan-ignore-line
    }

    /**
     * @covers ::dispatch
     */
    public function testDispatch(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('stdClass', function ($event): void {});

        $event = new \stdClass();
        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    /**
     * @covers ::dispatch
     */
    public function testDispatchWithStoppedEvent(): void
    {
        $event = new class () extends AbstractStoppableEvent {};

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener($event, function ($event): void {
            throw new \RuntimeException('This should never run');
        });

        $event->stopPropagation();

        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    /**
     * @covers ::dispatch
     */
    public function testItWillReturnTheSameEventWhenNoListeners(): void
    {
        $dispatcher = new EventDispatcher();

        $event = new \stdClass();
        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    /**
     * @covers ::addListener
     */
    public function testItCanAddEventListener(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('add');

        $dispatcher = new EventDispatcher($provider);

        $dispatcher->addListener('stdClass', function (): void {});
    }

    /**
     * @covers ::addListener
     */
    public function testAddListenerWithObject(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('add')->with($this->identicalTo('stdClass'));

        $dispatcher = new EventDispatcher($provider);

        $dispatcher->addListener(new \stdClass(), function (): void {});
    }

    /**
     * @covers ::addSubscriber
     */
    public function testItCanAddEventSubscriber(): void
    {
        $provider = $this->createMock(ListenerProvider::class);
        $provider->expects($this->once())->method('addSubscriber');

        $dispatcher = new EventDispatcher($provider);

        $subscriber = $this->createMock(EventSubscriberInterface::class);

        $dispatcher->addSubscriber($subscriber);
    }
}
