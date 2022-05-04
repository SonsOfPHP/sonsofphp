<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
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
     *
     * @param MessageInterface $message
     *
     * @return void
     */
    public function persist(MessageInterface $message): void;

    /**
     * Return all messages for the aggregate.
     *
     * If a version is passed in, it will find all messages AFTER that message.
     *
     * @param AggregateIdInterface      $id
     * @param AggregateVersionInterface $version If the Version is passed in, it will return all
     *                                           messages greater than the version passed in.
     *
     * @thorws AggregateNotFoundException
     *
     * @return Generator
     */
    public function find(AggregateIdInterface $id, ?AggregateVersionInterface $version = null): Generator;
}
