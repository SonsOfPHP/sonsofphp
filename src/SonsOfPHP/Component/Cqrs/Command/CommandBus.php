<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Command;

use SonsOfPHP\Component\Cqrs\AbstractBus;
use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\Command\CommandBusInterface;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;

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
