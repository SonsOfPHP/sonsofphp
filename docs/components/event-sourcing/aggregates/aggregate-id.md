---
title: Aggregate IDs
---

# Aggregate IDs

Each Aggregate will need to have it's own unique ID. You can use the same
AggregateId class for all your aggregates or you can make you own for each one
to ensure data consistency.

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

If you need to create an Aggregate ID Class in order to use type-hinting, just
extend the `AbstractAggregateId` class.

```php
<?php

use SonsOfPHP\Component\EventSourcing\Aggregate\AbstractAggregateId;

final class UserId extends AbstractAggregateId
{
}
```

#### Auto Generate Aggregate IDs

!!! attention
    This requires the installation of the `sonsofphp/event-sourcing-symfony` package.

Creating your own implementation to generate UUIDs or other type of IDs can be a
pain in the ass. Once the `sonsofphp/event-sourcing-symfony` package is
installed, you can easily use Symfony's Uid Component to generate UUIDs for you.

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
