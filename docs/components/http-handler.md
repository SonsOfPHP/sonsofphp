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
$stack->add(function ($request, $handler) {
    // ...
});
// ...

$app = new HttpHandler($stack);
$response = $app->handle($request);
```

The `MiddlewareStack` accepts objects that implement `Psr\Http\Server\MiddlewareInterface`
and anonymous functions.

### Middleware Priorities

An optional second argument may be passed to the `MiddlewareStack` which is for
the priority of the middleware. Priorities are ordered in ascending order.

```php
<?php

use SonsOfPHP\Component\HttpHandler\MiddlewareStack;

$stack = new MiddlewareStack();
$stack->add(new NotFoundMiddleware(), 1025);
$stack->add(new RouterMiddleware(), 255);
$stack->add(new CookieMiddleware(), -255);
$stack->add(new DefaultMiddleware());
```

In the above example, the `CookieMiddleware` will be processed first and
`NotFoundMiddleware` will be processed last.
