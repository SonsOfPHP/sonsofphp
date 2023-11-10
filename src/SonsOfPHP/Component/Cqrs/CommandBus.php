<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Contract\Cqrs\CommandBusInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CommandBus extends AbstractBus implements CommandBusInterface
{
    public function dispatch(object $command): void
    {
        $handler = $this->provider->getHandlerForMessage($command);

        $handler($command, $this);
    }
}
