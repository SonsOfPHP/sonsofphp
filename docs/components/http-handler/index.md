---
title: HttpHandler
---

Simple PSR-15 Http Handler

## Installation

```shell
composer require sonsofphp/http-handler
```

## Usage

Usage is pretty simple.

```php
<?php

use SonsOfPHP\Component\HttpHandler\HttpHandler;
use SonsOfPHP\Component\HttpHandler\MiddlewareStack;

$stack = new MiddlewareStack();
$stack->add(new RouterMiddleware());
$stack->add(new CookieMiddleware());
// ...

$app = new HttpHandler($stack);
$response = $app->handle($request);
```
