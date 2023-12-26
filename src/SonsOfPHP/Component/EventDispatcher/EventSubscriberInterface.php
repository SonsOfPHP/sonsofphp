<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EventSubscriberInterface
{
    /**
     * Returns an iterable with one or more of the following
     *   - ['eventName' => 'methodName'], ...
     *   - ['eventName' => ['methodName', $priority], ...], ...
     *   - ['eventName' => [['methodName', $priority], ['methodName']], ...], ...
     */
    public static function getSubscribedEvents();
}
