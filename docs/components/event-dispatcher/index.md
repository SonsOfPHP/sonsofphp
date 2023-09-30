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
$dispatcher->addListener('event.name', function () {}, $priority);
```

The priority will default to `0`. Lower numbers are higher priority. Higher
numbers will be handled later. For example, a listener with a priority of `-1`
will be handled before a listener of priority `1`.
