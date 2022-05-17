<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Bridge\Symfony;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class CommandMessageBus
{
    private MessageBusInterface $messageBus;
    private array $stamps = [];

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * Allows you to dispatch the command with additional stamps if
     * needed.
     *
     * @param StampInterface[] $stamps
     *
     * @return static
     */
    public function withStamps(array $stamps): CommandMessageBus
    {
        $that = clone $this;
        $that->stamps = $stamps;

        return $that;
    }

    /**
     * Dispatch the command.
     */
    public function dispatch(object $command): Envelope
    {
        return $this->messageBus->dispatch($command, $this->stamps);
    }
}
