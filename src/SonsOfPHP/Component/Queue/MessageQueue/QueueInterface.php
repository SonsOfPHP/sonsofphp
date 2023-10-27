<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Queue\MessageQueue;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface QueueInterface
{
    public function getName(): string;

    public function publish(MessageInterface $message): void;

    public function receive(): iterable;

    public function delete(MessageInterface $message): void;

    public function purge(): void;
}
