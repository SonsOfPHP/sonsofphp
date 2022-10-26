<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Message\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersionInterface;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class InMemoryMessageRepository implements MessageRepositoryInterface
{
    private array $storage = [];

    /**
     * {@inheritdoc}
     */
    public function persist(MessageInterface $message): void
    {
        $id = $message->getAggregateId();
        $version = $message->getAggregateVersion();

        $this->storage[$id->toString()][$version->toInt()] = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function find(string|AggregateIdInterface $id, int|AggregateVersionInterface $version = null): iterable
    {
        if (!$id instanceof AggregateIdInterface) {
            $id = new AggregateId($id);
        }

        if (\is_int($version)) {
            $version = new AggregateVersion($version);
        }

        if (!isset($this->storage[$id->toString()])) {
            throw new AggregateNotFoundException('no aggregate found in storage');
        }

        foreach ($this->storage[$id->toString()] as $ver => $message) {
            if (null !== $version && $ver <= $version->toInt()) {
                continue;
            }

            yield $message;
        }
    }
}
