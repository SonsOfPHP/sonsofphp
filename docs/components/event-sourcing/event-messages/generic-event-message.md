---
title: Generic Event Message - Event Sourcing
---

# Using the Serializable Event Message

The `AbstractSerializableMessage` allows you to quickly create Event Messages that
are dispatched.

## Usage

### Event Message Class

First you will need to create your Event Message Class.

``` php
<?php
use SonsOfPHP\Component\EventSourcing\Message\AbstractSerializableMessage;

class AggregateFieldChangedEventMessage extends AbstractSerializableMessage
{
}
```

### Initializing Event Message Class

Once you create you Event Message Class, using it is very easy.

``` php
<?php
$event = AggregateFieldChangedEventMessage::new()->withPayload([
    'previous_value' => $previousValue,
    'new_value'      => $newValue,
]);
```
