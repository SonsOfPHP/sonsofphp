---
title: Container
description: PSR-11 Container
---

# Container

### Installation

```shell
composer require sonsofphp/container
```

### Usage

```php
<?php

use SonsOfPHP\Component\Container\Container;
use Psr\Container\ContainerInterface;

$container = new Container();
$container->set('service.id.one', function (ContainerInterface $container) {
    return new Service();
});
$container->set('service.id.two', function (ContainerInterface $container) {
    return new Service($container->get('service.id.one'));
});

// Services will not be created until they are called, once called, they will
// always return the same instance of the service. That means that in the
// following code, the "service.id.two" is only constructed once.
$service  = $container->get('service.id.two');
$service2 = $container->get('service.id.two');
```
