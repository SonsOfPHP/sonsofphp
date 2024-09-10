---
title: Logger (PSR-3 Compatible)
---

# Logger

Simple yet powerful PSR-3 Logger.

### Installation

```shell
composer require sonsofphp/logger
```

### Usage

Simple Usage Example

```php
<?php

use SonsOfPHP\Component\Logger\Logger;

// Logger is PSR-3 Logger
$logger = new Logger();
$logger->debug('Debug Log Message');
```

Full Usage Example

```php
<?php

use SonsOfPHP\Component\Logger\Logger;
use SonsOfPHP\Component\Logger\Level;

// "api" will be the channel
$logger = new Logger('api');

// Add as many handlers as you want
$logger->addHandler(new FileHandler('/var/logs/api.log'));

// Add as many filters as you want
$logger->addEnricher(new MaskContextValueEnricher('password'));

// You can add a filter
$logger->setFilter(new LogLevelFilter(Level::Info)); // ONLY log info and above messages

// Filters can also be added to handlers
$handler = new FileHandler('/var/logs/api.alert.log');

// This handler will now ONLY handle records that are 'alert' and higher
$handler->setFilter(new LogLevelFilter(Level::Alert));
$logger->addHandler($handler);
```

#### Handlers

Handlers are responsible for "handling" the log message. The handler will send the log message where it's been configured to. Out of the box, it supports a few different handlers.

#### Enrichers

Enrichers will add extra context to the log message. This could be the git hash or memory usage, or anything else you want.

Enrichers are also used to modify context values in case someone adds sensitive values in there.

#### Filters

Filters can be used to determine it the log message should be handled. Custom filters could be specific to an HTTP Request so that ONLY requests matching a specific path would be handled. You could also make a custom filter to log only authenticated users

#### Formatters

Formatters are used by Handlers to format the message that needs to be logged.
