<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Supports
 *   - Event Names like "event.name"
 *   - Listener Priorities
 *   - Event Subscribers
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ListenerProvider implements ListenerProviderInterface
{
    private array $listeners = [];
    private array $sorted    = [];

    public function getListenersForEvent(object $event): iterable
    {
        return $this->getListenersForEventName($event::class);
    }

    public function add(string $eventName, callable|array $listener, int $priority = 0): void
    {
        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sorted[$eventName]);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        foreach ($subscriber::getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                // 'eventName' => 'methodName'
                $this->add($eventName, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                // 'eventName' => ['methodName', $priority]
                $this->add($eventName, [$subscriber, $params[0]], $params[1] ?? 0);
            } else {
                // 'eventName' => [['methodName1', $priority], ['methodName2']]
                foreach ($params as $listener) {
                    $this->add($eventName, [$subscriber, $listener[0]], $listener[1] ?? 0);
                }
            }
        }
    }

    public function getListenersForEventName(string $eventName): iterable
    {
        if (!\array_key_exists($eventName, $this->listeners)) {
            return [];
        }

        if (!isset($this->sorted[$eventName])) {
            $this->sortListeners($eventName);
        }

        return $this->sorted[$eventName];
    }

    private function sortListeners(string $eventName): void
    {
        ksort($this->listeners[$eventName]);
        $this->sorted[$eventName] = [];

        foreach ($this->listeners[$eventName] as $listeners) {
            foreach ($listeners as $listener) {
                $this->sorted[$eventName][] = $listener;
            }
        }
    }
}
