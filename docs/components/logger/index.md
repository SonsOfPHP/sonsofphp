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

```php
<?php

use SonsOfPHP\Component\Logger\Logger;

$logger = new Logger(channel: 'app', handlers: [], enrichers: []);
$logger->debug('Debug Log Message', context: []);
```

### Handlers

Handlers will HANDLE the message. This could be simply be writing the log
message to a log file.

### Enrichers

Enrichers will add extra metadata to the log message. This could be the git
hash or memory usage, or anything else you want.
