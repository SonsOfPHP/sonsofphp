<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

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
