<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private ListenerProviderInterface $provider = new ListenerProvider(),
    ) {}

    /**
     * @return object
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        $eventName ??= $event::class;

        foreach ($this->provider->getListenersForEventName($eventName) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }

            $listener($event, $eventName, $this);
        }

        return $event;
    }

    public function addListener(string $eventName, callable|array $listener, int $priority = 0): void
    {
        $this->provider->add($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        $this->provider->addSubscriber($subscriber);
    }
}
