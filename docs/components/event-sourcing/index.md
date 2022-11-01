---
title: Event Sourcing - Overview
---

# Event Sourcing

Using the Event Sourcing Component allows you to manage an Event Sourced
application.

## Installation

```shell
composer require sonsofphp/event-sourcing
```

### Additional Features

Additional features and functionality can be enabled by installing additional
packages.

#### Doctrine

Installing the Doctrine Bridge will allow the storage of event messages in a
database.

```shell
composer require sonsofphp/event-sourcing-doctrine
```

#### Symfony

Installing the Symfony Bridge will add addition functionality such as allowing
events to be sent asynchronously using the Messenger Component. It allows has
options to generate UUID/ULID for Aggregates.

```shell
composer require sonsofphp/event-sourcing-symfony
```
