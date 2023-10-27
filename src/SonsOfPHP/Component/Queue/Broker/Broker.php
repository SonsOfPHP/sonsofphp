<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Queue\Broker;

use SonsOfPHP\Component\Queue\Exception\QueueException;
use SonsOfPHP\Component\Queue\MessageQueue\QueueInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class Broker implements BrokerInterface
{
    private array $queues = [];

    public function __construct(
        array $queues = [],
    ) {
        foreach ($queues as $queue) {
            $this->add($queue);
        }
    }

    public function get(string $name): QueueInterface
    {
        if (!array_key_exists($queue->getName(), $this->queues)) {
            throw new QueueException();
        }

        return $this->queues[$queue->getName()];
    }

    public function add(QueueInterface $queue): void
    {
        if (array_key_exists($queue->getName(), $this->queues)) {
            throw new QueueException();
        }

        $this->queues[$queue->getName()] = $queue;
    }
}
