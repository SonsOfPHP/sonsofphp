---
title: HttpHandler
---

Simple PSR-15 Http Handler

## Installation

```shell
composer require sonsofphp/http-handler
```

### Usage

Usage is pretty simple.

```php
<?php

use SonsOfPHP\Component\HttpHandler\HttpHandler;

$middlewares = [];
$middlewares[] = new CookieMiddleware();
// ...

$app = new HttpHandler($middlewares);
$response = $app->handle($request);
```
