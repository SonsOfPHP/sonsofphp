<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Contract\Cqrs\QueryBusInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class QueryBus extends AbstractBus implements QueryBusInterface
{
    public function handle(object $command): mixed
    {
        $handler = $this->provider->getHandlerForMessage($command);

        return $handler($command, $this);
    }
}
