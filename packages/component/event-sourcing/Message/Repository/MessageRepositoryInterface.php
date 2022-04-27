<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Generator;

/**
 * Message Repository Interface
 *
 * A Message Repository is responsible for saving and retrieving messages
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageRepositoryInterface
{
    /**
     * Store the message
     */
    public function persist(MessageInterface $message): void;

    /**
     * Return all messages for the aggregate.
     *
     * If a version is passed in, it will find all messages AFTER that message.
     */
    public function find(AggregateIdInterface $id, ?AggregateVersionInterface $version = null): Generator;
}
