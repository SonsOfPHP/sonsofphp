---
title: Http Message (PSR-7)
description: PHP PSR-7 implementation
---

# Http Message Component

Simple PSR-7 Compatible Http Message Component

## Installation

```shell
composer require sonsofphp/http-message
```

## Usage

### Uri

```php
<?php

use SonsOfPHP\Component\HttpMessage\Uri;

// Passing in the url is optional.
$uri = new Uri('https://docs.sonsofphp.com');

// You can also pass in a more detailed url
$uri = new Uri('https://docs.sonsofphp.com/search/results?q=test');

// You can also easily add additional query parameters
$uri = $uri->withQueryParams([
    'page'    => 1,
    'limit'   => 25,
    'filters' => [
        'isActive' => 1,
    ]
]);

// To remove all the query parameters, pass in `null`
$uri = $uri->withQueryParams(null);
```
