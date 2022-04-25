<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use Generator;

/**
 * Aggregate Trait
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
trait AggregateTrait
{
    private AggregateIdInterface $id;
    private AggregateVersionInterface $version;
    private array $pendingEvents = [];

    /**
     * @param AggregateIdInterface $id
     *
     * @return static
     */
    public static function new(AggregateIdInterface $id)
    {
        $static = new static();
        $static->id = $id;
        $static->version = AggregateVersion::zero();

        return $static;
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregateId(): AggregateIdInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregateVersion(): AggregateVersionInterface
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function yieldEvents(): Generator
    {
        foreach ($this->pendingEvents as $event) {
            yield $event;
        }

        $this->pendingEvents = [];
    }

    /**
     * {@inheritdoc}
     */
    public static function buildFromEvents(AggregateIdInterface $id, Generator $events): AggregateInterface
    {
        $aggregate = static::new($id);
    }

    /**
     */
    protected function raiseEvent(MessageInterface $event): void
    {
        $this->applyEvent($event);
        $this->pendingEvents[] = $event;
    }

    /**
     */
    protected function applyEvent(MessageInterface $event): void
    {
        $parts  = explode('\\', get_class($event));
        $method = 'apply'.end($parts);

        if (method_exists($this, $method)) {
            $this->$method($event);
        }

        $this->version = $this->version->next();
    }
}
