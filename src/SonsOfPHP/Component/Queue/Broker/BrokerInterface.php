<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Queue\Broker;

/**
 * Example Usage:
 *   $broker = new Broker([$fileQueue]);
 *   $broker->add($queue);
 *   $broker->get('queue.name')->publish($message);
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface BrokerInterface
{
    public function get(string $name): QueueInterface;

    public function add(QueueInterface $queue): void;
}
