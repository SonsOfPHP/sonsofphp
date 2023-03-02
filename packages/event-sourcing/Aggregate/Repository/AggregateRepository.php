<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate\Repository;

use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Exception\AggregateNotFoundException;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricherInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Provider\NullMessageEnricherProvider;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AggregateRepository implements AggregateRepositoryInterface
{
    public function __construct(
        protected string $aggregateClass,
        protected EventDispatcherInterface $eventDispatcher,
        protected MessageRepositoryInterface $messageRepository,
        protected ?MessageEnricherInterface $messageEnricher = null
    ) {
        $this->messageEnricher = $messageEnricher ?? new MessageEnricher(new NullMessageEnricherProvider());
    }

    /**
     * {@inheritDoc}
     */
    public function find(AggregateIdInterface|string $id): ?AggregateInterface
    {
        if (!$id instanceof AggregateIdInterface) {
            $id = new AggregateId($id);
        }

        try {
            $events         = $this->messageRepository->find($id);
            $aggregateClass = $this->aggregateClass;

            return $aggregateClass::buildFromEvents($id, $events);
        } catch (AggregateNotFoundException $e) {
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(AggregateInterface $aggregate): void
    {
        $events           = $aggregate->getPendingEvents();
        $eventsToDispatch = [];

        foreach ($events as $message) {
            // We want to enrich the message BEFORE sending to dispatcher or being
            // persisted
            $message = $this->messageEnricher->enrich($message);
            $this->messageRepository->persist($message);
            $eventsToDispatch[] = $message;
        }

        foreach ($eventsToDispatch as $message) {
            $this->eventDispatcher->dispatch($message);
        }
    }
}
