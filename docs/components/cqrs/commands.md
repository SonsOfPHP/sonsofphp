---
title: Commands
---

# Commands

All Commands must implement the [`CommandMessageInterface`][CommandMessageInterface].

## Basic Command

Simple Commands can be created quickly and easily.

```php
<?php

use SonsOfPHP\Component\Cqrs\Command\CommandMessageInterface;

final class SendPasswordResetEmailToUser implements CommandMessageInterface
{
    public function __construct(
        public readonly string $userId,
    ) {
        $this->userId = $userId;
    }
}
```

## Options Resolver Command

!!! attention
    The `AbstractOptionsResolverCommand` reqires the `sonsofphp/cqrs-symfony`
    package.

The Options Resolver Command allows you to create more complex commands. For
example.

```php
<?php

use SonsOfPHP\Bridge\Symfony\Cqrs\Command\AbstractOptionsResolverCommandMessage;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SendPasswordResetEmailToUser extends AbstractOptionsResolverCommandMessage
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('user_id')
            ->required()
            ->allowedTypes('int')
            ->allowedValues(function ($value) {
                return $value > 0;
            });

    }
}
```

To use the command, you just create a new instance of the command.

```php
<?php

$command = new SendPasswordResetEmailToUser(['user_id' => 1234]);

// ...

// Get an option
$userId = $command->getOption('user_id');

// Get ALL options
$options = $command->getOptions():

// Check if option exists
$command->hasOption('user_id');
```

[CommandMessageInterface]: https://github.com/SonsOfPHP/sonsofphp/blob/main/packages/cqrs/Command/CommandMessageInterface.php
