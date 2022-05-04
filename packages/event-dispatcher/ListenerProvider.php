<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class ListenerProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    /**
     */
    public function add(string $event, $listener)
    {
        $this->listeners[$event][] = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        $class = get_class($event);
        if (isset($this->listeners[$class])) {
            return $this->listeners[$class];
        }

        return [];
    }
}
