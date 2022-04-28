<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony;

use Symfony\Component\Messenger\MessageBusInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageBusBridge implements EventDispatcherInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event)
    {
        $this->eventBus->dispatch($event);
    }
}