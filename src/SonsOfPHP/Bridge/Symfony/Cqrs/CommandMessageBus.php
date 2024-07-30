<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Command Bus that uses Symfony Messenger.
 *
 * If using Symfony Framework, add to your services.yaml
 * <code>
 * SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus:
 *     arguments: ['@command.bus']
 * </code>
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 *
 * @see https://symfony.com/doc/current/messenger/multiple_buses.html
 * @see https://symfony.com/doc/current/messenger.html
 * @see https://symfony.com/doc/current/components/messenger.html
 */
class CommandMessageBus
{
    private array $stamps = [];

    public function __construct(private MessageBusInterface $messageBus) {}

    /**
     * Allows you to dispatch the command with additional stamps if
     * needed.
     *
     * @param StampInterface[] $stamps
     *
     * @return static
     */
    public function withStamps(array $stamps): self
    {
        $that         = clone $this;
        $that->stamps = $stamps;

        return $that;
    }

    /**
     * Dispatch the command.
     */
    public function dispatch(object $command): object
    {
        return $this->messageBus->dispatch($command, $this->stamps);
    }
}
