<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * Message Repository Interface.
 *
 * A Message Repository is responsible for saving and retrieving messages
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface MessageRepositoryInterface
{
    /**
     * Store the message.
     *
     * @todo Return the $message. This way, the serializer can enrich the message
     */
    public function persist(MessageInterface $message): void;

    /**
     * Return all messages for the aggregate.
     *
     * If a version is passed in, it will find all messages AFTER that message.
     *
     * @param AggregateVersionInterface|int|null $version if the Version is passed in, it will return all
     *                                                    messages greater than the version passed in
     *
     * @thorws AggregateNotFoundException
     */
    public function find(string|AggregateIdInterface $id, int|AggregateVersionInterface $version = null): iterable;
}
