---
title: Logger (PSR-3 Compatible)
description: PHP Logger
---

Simple yet powerful PSR-3 Logger.

## Installation

```shell
composer require sonsofphp/logger
```

## Usage

Simple usage

```php
<?php

use SonsOfPHP\Component\Logger\Logger;

// Logger is PSR-3 Logger
$logger = new Logger('app');
// ...
$logger->debug('Debug Log Message');
```

More advanced usage

```php
<?php

use SonsOfPHP\Component\Logger\Logger;
use SonsOfPHP\Component\Logger\Level;

// "api" will be the channel
$logger = new Logger('api');

// Add as many handlers as you want
$logger->addHandler(new SyslogHandler());

// Add as many filters as you want
$logger->addEnricher(new RemoveCreditCardNumberEnricher());
$logger->addEnricher(new AddHttpRequestEnricher());

// You can add a filter
$logger->setFilter(new LogLevelFilter(Level::Info)); // ONLY log info and above
messages

// Filters can also be added to handlers
$handler = new SyslogHander();

// This handler will now ONLY handle records that are 'alert' and higher
$handler->setFilter(new LogLevelFilter(Level::Alert));
$logger->addHandler($handler);
```

### Handlers

Handlers will HANDLE the message. This could be simply be writing the log
message to a log file.

### Enrichers

Enrichers will add extra metadata to the log message. This could be the git
hash or memory usage, or anything else you want.

### Filters

Filters can be used to determine it the log message should be handled. Custom
filters could be specific to an HTTP Request so that ONLY requests matching a
specific path would be handled. You could also make a custom filter to log only
authenticated users

### Formatters

Formatters are used by Handlers to format the message that needs to be logged.
