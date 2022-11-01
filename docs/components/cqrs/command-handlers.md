---
title: Command Handlers
---

# Command Handlers

Once you create a Command, you need to create a Command Handler to handle that
command. Command Handlers do NOT return any results.

```php
<?php

use SonsOfPHP\Component\Cqrs\Command\CommandMessageHandlerInterface;

final class ExampleCommandHandler implements CommandMessageHandlerInterface
{
}
```

## Getting the Command to the Command Handler

!!! attention
    The `CommandMessageBus` reqires the `sonsofphp/cqrs-symfony`
    package.

You can use Messenger to process commands. If you're using Messenger as a
standalone component, inject your `MessageBus` object into the
`CommandMessageBus`.

```php
<?php

use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;

$commandBus = new CommandMessageBus($messageBus);
```

If using the Symfony Framework, it's even easier. You'll just need to update
your `services.yaml` file.

```yaml
SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus:
    arguments: ['@command.bus']
```

!!! note
    You will need to configure Messenger to have multiple buses. `command.bus`
    is the name of the bus that was configured to process commands.

Using the `CommandMessageBus` is easy as well.

```php
<?php

// Once you create a Command, it can just be dispatched.
$commandBus->dispatch($command);

// If you need to apply one or more stamps, this must be done
// before the command is dispatched.
$commandBus
    ->withStamps([$stamp])
    ->dispatch($command);
```
