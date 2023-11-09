---
title: CQRS
---

Command Query Responsibility Segregation is a simple concept. When you want
something to happen, you will use a Command. If you need results returned, you
will use a query.

An over simplified example of this would be if you need to write to a database,
you would use a Command. If you need results from a database, you would use a
Query.

## Installation

```shell
composer require sonsofphp/cqrs
```

## Basic Usage

### Command Bus

```php
<?php

use SonsOfPHP\Component\Cqrs\Command\CommandBus;

$commandBus = new CommandBus();
$commandBus->addHandler(CreateUser::class, $handler);

$command = new CreateUser();
$commandBus->dispatch($command);
```

!!! success "Symfony CQRS Bridge"
    Once the CQRS Symfony Bridge is installed, you can use the Command Bus that
    comes with that to gain access to additional features and functionality.

### Query Bus

```php
<?php

use SonsOfPHP\Component\Cqrs\Query\QueryBus;

$queryBus = new QueryBus();
$queryBus->addHandler(GetUser::class, $handler);

$query = (new GetUser())->with('id', 123);
$user  = $queryBus->handle($query);

!!! success "Symfony CQRS Bridge"
    Once the CQRS Symfony Bridge is installed, you can use the Query Bus that
    comes with that to gain addition features and functionality.
```

## Messages

Both Commands and Queries are considered to be messages. It is HIGHLY
recommended to use the `AbstractMessage` class for both Commands and Queries.
For all examples I will assume you have extended this class.

```php
<?php

use SonsOfPHP\Component\Cqrs\AbstractMessage;

class CreateUser extends AbstractMessage {}

$command = new CreateUser();
$command = $command->with('id', 123);
$command = $command->with('email', 'joshua@sonsofphp.com');

$userId  = $command->get('id'); // $userId = 123
```

```php
<?php

use SonsOfPHP\Component\Cqrs\AbstractMessage;

class GetUser extends AbstractMessage {}

$query = new GetUser();
$query = $query->with('id', 123);

$userId = $query->get('id'); // $userId = 123
```

!!! note
    `AbstractMessage` treats the message as a value object. So using `with` will
    return a new instance of the class.

### Additional `AbstractMessage` API

```php
<?php

use SonsOfPHP\Component\Cqrs\AbstractMessage;

class Message extends AbstractMessage {}

// Create a new instance with multiple paramters
$message = (new Message())->with([
    'key'     => 'value',
    'another' => 'value',
    // ...
]);

// Getting all the paramters of the message
$parameters = $message->get();

// Get a single paramter value
// WARNING: If the parameter is not found, an exception will be thrown
$userId = $message->get('user.id');
```

## Message Handlers

Both Command and Query Handers are assumed to just be message handlers.

### Command Handlers

```php
<?php

use SonsOfPHP\Contract\Cqrs\Command\CommandBusInterface;

class CreateUserHandler
{
    // First argument will be the Command (ie Message)
    // Second argument is the command bus
    public function __invoke(CreateUser $command, CommandBusInterface $bus): void
    {
        // ...
    }
}
```

### Query Handlers

```php
<?php

use SonsOfPHP\Contract\Cqrs\Query\QueryBusInterface;

class GetUserHandler
{
    // First argument will be the Query (ie Message)
    // Second argument is the query bus
    public function __invoke(GetUser $query, QueryBusInterface $bus): ?UserInterface
    {
        // ...
    }
}
```

## Symfony Bridge

The Symfony Bridge uses Symfony Components to add additional functionality to
the CQRS component.

### Installation

```shell
composer require sonsofphp/cqrs-symfony
```
