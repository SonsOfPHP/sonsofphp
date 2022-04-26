<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventDispatcher;

interface ListenerInterface
{
    public function __invoke(object $event);
}
