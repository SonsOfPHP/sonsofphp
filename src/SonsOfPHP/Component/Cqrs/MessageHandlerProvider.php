<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs;

use SonsOfPHP\Component\Cqrs\Exception\NoHandlerFoundException;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MessageHandlerProvider implements MessageHandlerProviderInterface
{
    private array $handlers = [];

    /**
     * Register a command with a command handler
     *
     * Usage:
     *   $cmd     = new CreateUser();
     *   $handler = new CreateUserHandler();
     *   $provider->register($cmd, $handler);
     *   ---
     *   $handler = new CreateUserHandler();
     *   $provider->register(CreateUser::class, $handler);
     *   ---
     *   $provider->register(CreateUser::class, function (CreateUser $cmd) {});
     */
    public function add(string|object $message, callable $handler): void
    {
        if (is_object($message)) {
            $message = $message::class;
        }

        $this->handlers[$message] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerForMessage(string|object $message): callable
    {
        if (is_object($message)) {
            $message = $message::class;
        }

        if (!array_key_exists($message, $this->handlers)) {
            throw new NoHandlerFoundException(sprintf('No handler for message "%s" found.', $message));
        }

        return $this->handlers[$message];
    }
}
