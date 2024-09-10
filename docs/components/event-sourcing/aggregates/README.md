---
title: Aggregates
---

# Aggregates

Aggregates are the primary objects that you will be working with.

## Aggregate ID

Each Aggregate will need to have it's own unique ID. You can use the same AggregateId class for all your aggregates or you can make you own for each one to ensure data consistency.

### Working with an Aggregate ID

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;

// Create a new Aggregate ID
$aggregateId = new AggregateId('my-unique-id');

// You can get the value of the ID
$id = $aggregateId->toString();
$id = (string) $aggregateId;

// To compare two Aggregate IDs
if ($aggregateId->equals($anotherAggregateId)) {
    // they are the same
}
```

If you need to create an Aggregate ID Class in order to use type-hinting, just extend the `AbstractAggregateId` class.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;

final class UserId extends AbstractAggregateId
{
}
```

#### Auto Generate Aggregate IDs

!!! attention This requires the installation of the `sonsofphp/event-sourcing-symfony` package.

Creating your own implementation to generate UUIDs or other type of IDs can be a pain in the ass. Once the `sonsofphp/event-sourcing-symfony` package is installed, you can easily use Symfony's Uid Component to generate UUIDs for you.

```php
<?php
use SonsOfPHP\Bridge\Symfony\EventSourcing\AggregateId;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\Ulid;

$id = new AggregateId();

Uuid::isValid($id->toString()); // true

// It also still supports setting the ID via the constructor
$ulid = new AggregateId((string) new Ulid());
```

## Aggregate Version

An Aggregate also has a version. Each event that is raised will increase the version. You will generally not have to work much with versions as they are mostly handled internally.

### Working with an Aggregate Version

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateVersion;

$aggregateVersion = new AggregateVersion();

// Get the version
$version = $aggregateVersion->toInt();

// Comparing Versions
if ($aggregateVersion->equals($anotherAggregateVersion)) {
    // they are the same
}
```

## Creating an Aggregate

Pretty simple to create an aggregate.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;

final class UserAggregate extends AbstractAggregate
{
}
```

Using the `AbstractAggregate` class takes care of all the heavy lifting. This allows you to focus on the different methods.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;

// Creating a new aggregate can be done by passing either a string
$userAggregate = new UserAggregate('unique-id');

// Or an object that implements `AggregateIdInterface`
$userAggregate = new UserAggregate(new AggregateId('unique-id'));
```

## Working with Event Messages

When you create an [Event Message](../event-messages/) you will need to raise that event within your aggregate and if need be, apply that event to the aggregate. Let me show you what I mean.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;
use SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage;

final class EmailUpdated extends AbstractSerializableMessage
{
    public function getEmail(): string
    {
        return $this->getPayload()['email'];
    }
}

final class UserAggregate extends AbstractAggregate
{
    private $email;

    public function setEmail(string $email)
    {
        $this->raiseEvent(EmailUpdated::new()->withPayload([
            'email' => $email,
        ]);
    }

    protected function applyEmailUpdated(EmailUpdated $message): void
    {
        $this->email = $message->getEmail();
    }
}
```

This is a very simplistic example. We should be doing a few checks on the email before the event is raised. What happens if we try to set an email and it's the same email as the user currently has? It would continue to raise an event.

You will also notice the `applyEmailUpdated` method on the class. This is optional, but when you raise an event, the `AbstractAggregate` will look for a method in the format of `apply{EventMessageClassname}`. So if the event classname was `DrankABeer` it would look for the method `applyDrankABeer` and if there is one, it will pass in the `DrankABeer` event message.
