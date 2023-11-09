<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cqrs;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageHandlerProviderInterface
{
    /**
     * Returns the handler for a given object
     *
     * Message is the Message that needs to be handled. This can either
     * be the message class name or the message object
     *
     * @throws \SonsOfPHP\Contract\Cqrs\Exception\NoHandlerExceptionInterface
     *   When no handler is available for the message
     *
     * Usage:
     *   $msg = new CreateUser();
     *   $handler = $provider->getHandlerForMessage($msg);
     *   ---
     *   $handler = $provider->getHandlerForMessage(CreateUser::class);
     */
    public function getHandlerForMessage(string|object $message): callable;
}
