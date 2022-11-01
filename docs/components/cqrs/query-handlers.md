---
title: Query Handlers
---

# Query Handlers

Query Handlers will handle a Query and return a result.

```php
<?php

use SonsOfPHP\Component\Cqrs\Query\QueryMessageHandlerInterface;

final class ExampleQueryHandler implements QueryMessageHandlerInterface
{
}
```

## Getting the Query to the Query Handler

!!! attention
    The `QueryMessageBus` reqires the `sonsofphp/cqrs-symfony`
    package.

You can use Messenger to process queries. If you're using Messenger as a
standalone component, inject your `MessageBus` object into the
`QueryMessageBus`.

```php
<?php

use SonsOfPHP\Bridge\Symfony\Cqrs\QueryMessageBus;

$queryBus = new QueryMessageBus($messageBus);
```

If using the Symfony Framework, it's even easier. You'll just need to update
your `services.yaml` file.

```yaml
SonsOfPHP\Bridge\Symfony\Cqrs\QueryMessageBus:
    arguments: ['@query.bus']
```

!!! note
    You will need to configure Messenger to have multiple buses. `query.bus`
    is the name of the bus that was configured to process queries.

Using the `QueryMessageBus` is easy as well.

```php
<?php

// Once you create a Query, it can just be handled.
$queryBus->handle($query);
```
