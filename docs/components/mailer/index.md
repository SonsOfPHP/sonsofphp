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

$mailer = new Mailer(new NullTransport());

$message = new Message();
$message->addHeader('To', 'joshua@sonsofphp.com');
$message->addHeader('From', 'joshua@sonsofphp.com');
$message->addHeader('Subject', 'Test Subject');
$message->setBody($body);

$mailer->send($message);
```
