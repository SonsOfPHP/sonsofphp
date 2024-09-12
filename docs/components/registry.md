---
title: Registry
---

## Installation

```shell
composer require sonsofphp/registry
```

## Usage

```php
<?php

use SonsOfPHP\Component\Registry\ServiceRegistry;

$registry = new ServiceRegistry($interfaceClassName);

$registry->register('service.id', $service);
$service = $registry->get('service.id');

if ($registry->has('service.id')) {
    // ...
}

$registry->unregister('service.id');

foreach ($registry->all() as $identifier => $service) {
    // ...
}
```
