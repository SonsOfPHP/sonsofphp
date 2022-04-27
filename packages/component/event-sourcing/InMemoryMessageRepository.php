<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use Generator;

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
        $id      = $message->getAggregateId();
        $version = $message->getAggregateVersion();

        $this->storage[$id->toString()][$version->toInt] = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function find(AggregateIdInterface $id, ?AggregateVersionInterface $version = null): Generator
    {
        if (!isset($this->storage[$id->toString()])) {
            throw new EventSourcingException('no aggregate found in storage');
        }

        foreach ($this->storage[$id->toString()] as $ver => $message) {
            if ($version && $ver <= $version->toInt()) {
                continue;
            }

            yield $message;
        }
    }
}
