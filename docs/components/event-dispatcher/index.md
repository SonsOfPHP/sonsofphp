---
title: Event Dispatcher - Overview
---

# Event Dispatcher

Event Dispatcher will allow you to attach listeners and notify those listeners
so they can handle the event that you dispatch. The Event Dispatcher is PSR-14
Compatible with extra features.

## Features

* Event Names
* Event Subscribers
* Listener Priorities


## Installation

```shell
composer require sonsofphp/event-dispatcher
```

## Usage

```php
<?php

use SonsOfPHP\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();

// If you have a custom ListenerProviderInterface you can inject it into the
// EventDispatcher
//$dispatcher = new EventDispatcher($provider);

$dispatcher->addListener($event::class, function ($event, $eventName, $dispatcher) {});
$dispatcher->addListener('event.name', function ($event, $eventName, $dispatcher) {});
$dispatcher->addSubscriber($subscriber);

$dispatcher->dispatch($event); // PSR-14
$dispatcher->dispatch($event, 'event.name'); // Custom Event Name
```

### Event Subscribers

Must implement `EventSubscriberInterface`.

### Listener Priorities

```php
<?php

$dispatcher->addListener('event.name', function () {}, $priority);
```

The priority will default to `0`. Lower numbers are higher priority. Higher
numbers will be handled later. For example, a listener with a priority of `-1`
will be handled before a listener of priority `1`.


### Stoppable Events

If your code extends the `AbstractStoppableEvent` and within your listener or
subscriber code, you execute `$this->stopPropagation();` and it will return the
event and no more listeners or subscribers will handle that event.

```php
<?php

use SonsOfPHP\Component\EventDispatcher\AbstractStoppableEvent;

class OrderCreated extends AbstractStoppableEvent
{
    // ...
}

class OrderListener
{
    public function __invoke(OrderCreated $event): void
    {
        // ...
        $event->stopPropagation();
        // ...
    }
}
```

## Creating an Event Listener

```php
<?php

use Psr\EventDispatcher\EventDispatcherInterface;

class OrderListener
{
    public function __invoke(OrderCreated $event, string $eventName, EventDispatcherInterface $dispatcher): void
    {
        // ...
    }
}
```

The dispatcher will always invoke the Listener with those three arguments in
that order. If you do not need to know the event name or if you do not need the
event dispatcher, you can ignore those two arguments.

## Creating an Event Subscriber

Subscribers allow you to "subscribe" to multiple events.

```php
<?php

use OrderCreated;
use OrderUpdated;
use OrderDeleted;
use SonsOfPHP\Component\EventDispatcher\EventSubscriberInterface;

class OrderSubscriber implements EventSubscriberInterface
{
    // ...

    public static function getSubscribedEvents()
    {
        // Can return like this:
        yield OrderCreated::class => 'onCreated';
        yield OrderUpdated::class => ['onUpdated', 100];
        yield OrderDeleted::class => [['onDeleted', 100], ['doFirstOnDeleted', -100]];

        // OR like this
        return [
            OrderCreated::class => 'onCreated',
            OrderUpdated::class => ['onUpdated', 100],
            OrderDeleted::class => [['onDeleted', 100], ['doFirstOnDeleted', -100]],
        ];
    }
}
```
