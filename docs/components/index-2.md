---
title: Cookie
---

## Installation

```shell
composer require sonsofphp/cookie
```

## Usage

A Cookie is treated as a value object. This means that if two cookie objects
have the same name and value, they will be considered equal. They are also
considered to be immutable.

```php
<?php

use SonsOfPHP\Component\Cookie\Cookie;

$cookie = new Cookie('name', 'value');

header('Set-Cookie: ' . $cookie->getHeaderValue());
// OR
// header('Set-Cookie: ' . (string) $cookie);

// Set various attributes
$cookie = $cookie
    ->withPath('/')
    ->withDomain('docs.sonsofphp.com')
;
```
