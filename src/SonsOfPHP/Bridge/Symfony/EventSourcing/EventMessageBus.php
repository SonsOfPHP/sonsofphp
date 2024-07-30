<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class EventMessageBus implements EventDispatcherInterface
{
    public function __construct(private readonly MessageBusInterface $eventBus) {}

    /**
     * @return object
     */
    public function dispatch(object $event)
    {
        return $this->eventBus->dispatch($event);
    }
}
