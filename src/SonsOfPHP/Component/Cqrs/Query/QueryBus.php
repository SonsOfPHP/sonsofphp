<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Query;

use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Component\Cqrs\AbstractBus;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\Query\QueryBusInterface;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;

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
