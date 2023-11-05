<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing;

use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepository;
use SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\InMemoryMessageRepository;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class AggregateClassMetadata implements AggregateClassMetadataInterface
{
    public function __construct(
        private string $name,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass($this->getName());
    }

    public function getAggregateRepositoryClass(): AggregateRepositoryInterface
    {
        return AggregateRepository::class;
    }

    public function getMessageRepositoryClass(): MessageRepositoryInterface
    {
        return InMemoryMessageRepository::class;
    }
}
