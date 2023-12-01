---
title: Cookie
---

## Installation

```shell
composer require sonsofphp/cookie
```

## Usage

```php
<?php

use SonsOfPHP\Component\Cookie\Cookie;

$cookie = new Cookie('name', 'value');
$cookie->send(); // Sends Set-Cookie Http Header
```
