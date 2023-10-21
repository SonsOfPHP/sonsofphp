<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface EventSubscriberInterface
{
    /**
     */
    public static function getSubscribedEvents();
}
