<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Component\Cqrs\AbstractMessage;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\Command\CommandBusInterface;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
abstract class AbstractBus
{
    public function __construct(
        protected MessageHandlerProviderInterface $provider = new MessageHandlerProvider(),
    ) {}

    public function addHandler(object|string $message, callable $handler): void
    {
        $this->provider->add($message, $handler);
    }
}
