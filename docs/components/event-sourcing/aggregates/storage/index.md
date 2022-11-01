---
title: Aggregate Storage
---

# Aggregate Repository

The `AggregateRepository` is the main interface used to persist and find
aggregates.

## Usage

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\Repository\AggregateRepository;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateIdInterface;
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateInterface;
use SonsOfPHP\Component\EventSourcing\Message\Repository\MessageRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

$repository = new AggregateRepository(
    $aggregateClass, // @var string
    $eventDispatcher, // @var EventDispatcherInterface
    $messageRepository // @var MessageRepositoryInterface
);

// @var AggregateIdInterface    $aggregateId
// @var AggregateInterface|null $aggregate
$aggregate = $repository->find($aggregateId);

// You can also pass in a string as the $aggregateId
$aggregate = $repository->find('unique-id');

// To save an aggregate
$repository->persist($aggregate);
```
