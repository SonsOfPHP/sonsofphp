---
title: Mailer
---

Simple PHP Mailer

## Installation

```shell
composer require sonsofphp/mailer
```

## Usage

```php
<?php

use SonsOfPHP\Component\Mailer\Message;
use SonsOfPHP\Component\Mailer\Mailer;
use SonsOfPHP\Component\Mailer\Transport\NullTransport;

$message = new Message();
$message
    ->setTo('joshua@sonsofphp.com')
    ->setFrom('From', 'joshua@sonsofphp.com')
    ->setSubject('Subject', 'Test Subject')
    ->setBody($body)
;

$mailer = new Mailer(new NullTransport());
$mailer->send($message);
```

### Middleware

The `Mailer` class supports various middleware as well.

```php
<?php

use SonsOfPHP\Component\Mailer\Mailer;
use SonsOfPHP\Component\Mailer\Transport\NullTransport;

$mailer = new Mailer(new NullTransport());
$mailer->addMiddleware($middleware);
```
