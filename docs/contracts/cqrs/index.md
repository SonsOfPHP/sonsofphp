---
title: Cqrs Contract
---

In a nut shell, with CQRS you have Commands that will change the state of your
application and Queries that will return information about the state of your
application.

## Installation

```shell
composer require sonsofphp/cqrs-contract
```

## Messages

Both Commands and Queries are considered Messages and are handled by Message
Handlers.

## Message Handlers

When a message is dispatched/handled, it is handled by a message handler.

## Message Bus

The Message Bus is given a Message and will use a Message Handler to handle the
message. If it's a Query Message, the bus will return a result and for Command
Messages, it will not return anything.

## Message Handler Provider

Returns the Handler that will handle the Message. They do not actually handle or
process the message.
