<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Event Dispatcher
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    private ListenerProviderInterface $provider;

    public function __construct(ListenerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event)
    {
        $listeners = $this->provider->getListenersForEvent($event);
        foreach ($listeners as $listener) {
            $listener($event);

            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }
        }

        return $event;
    }
}
