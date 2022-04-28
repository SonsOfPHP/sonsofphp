<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Aggregate\Repository;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Exception\EventSourcingException;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricherInterface;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\MessageEnricher;;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\NullMessageEnricherProvider;;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class AggregateRepository implements AggregateRepositoryInterface
{
    protected string $aggregateClass;
    protected MessageRepositoryInterface $messageRepository;
    protected EventDispatcherInterface $eventDispatcher;
    protected MessageEnricherInterface $messageEnricher;

    public function __construct(
        string $aggregateClass,
        EventDispatcherInterface $eventDispatcher,
        MessageRepositoryInterface $messageRepository,
        ?MessageEnricherInterface $messageEnricher = null
    )
    {
        $this->aggregateClass    = $aggregateClass;
        $this->eventDispatcher   = $eventDispatcher;
        $this->messageRepository = $messageRepository;
        $this->messageEnricher   = $messageEnricher ?? new MessageEnricher(new NullMessageEnricherProvider());
    }

    /**
     * {@inheritdoc}
     */
    public function find(AggregateIdInterface $id): AggregateInterface
    {
        $events = $this->messageRepository->find($id);
        $aggregateClass = $this->aggregateClass;

        return $aggregateClass::buildFromEvents($id, $events);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(AggregateInterface $aggregate): void
    {
        $events = $aggregate->getPendingEvents();
        foreach ($events as $message) {
            $message = $this->messageEnricher->enrich($message);
            $this->messageRepository->persist($message);
            $this->eventDispatcher->dispatch($message);
        }
    }
}
