---
title: Aggregates
---

# Aggregates

Aggregates are the primary objects that you will be working with.

## Creating an Aggregate

Pretty simple to create an aggregate.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregate;

final class UserAggregate extends AbstractAggregate
{
}
```

Using the `AbstractAggregate` class takes care of all the heavy lifting. This
allows you to focus on the different methods.

```php
<?php
use SonsOfPHP\Component\EventSourcing\Aggregate\AggregateId;

// Creating a new aggregate can be done by passing either a string
$userAggregate = new UserAggregate('unique-id');

// Or an object that implements `AggregateIdInterface`
$userAggregate = new UserAggregate(new AggregateId('unique-id'));
```

## Working with Event Messages

When you create an [Event Message](../event-messages/index.md) you will need to
raise that event within your aggregate and if need be, apply that event to the
aggregate. Let me show you what I mean.

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

This is a very simplistic example. We should be doing a few checks on the email
before the event is raised. What happens if we try to set an email and it's the
same email as the user currently has? It would continue to raise an event.

You will also notice the `applyEmailUpdated` method on the class. This is
optional, but when you raise an event, the `AbstractAggregate` will look for a
method in the format of `apply{EventMessageClassname}`. So if the event
classname was `DrankABeer` it would look for the method `applyDrankABeer` and if
there is one, it will pass in the `DrankABeer` event message.
