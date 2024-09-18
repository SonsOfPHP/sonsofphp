---
title: Cache - Overview
---

## Installation

```shell
composer require sonsofphp/cache
```

### PSR-16 - Simple Cache

To add support for PSR-16 Simple Cache, require the PSR.

```shell
composer require psr/simple-cache
```

## Usage

```php
<?php

use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;

$pool = new ApcuAdapter();
```

All Adapters implement `Psr\Cache\CacheItemPoolInterface`

### PSR-16 Simple Cache Wrapper

```php
<?php

use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Component\Cache\SimpleCache;

$pool = new ApcuAdapter();
$cache = new SimpleCache($pool);
```

`SimpleCache` implements `Psr\SimpleCache\CacheInterface`.

### Setting up a multi layer cache

```php
<?php

use SonsOfPHP\Component\Cache\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Component\Cache\Adapter\ChainAdapter;

$pool = new ChainAdapter([
    new ArrayAdapter(),
    new ApcuAdapter(),
]);
```

The `ChainAdapter` will read from all pools and return the first result it
finds. So in the above example, if the cache item is not found in
`ArrayAdapter`, it will look for it in the `ApcuAdapter` pool.

The `ChainAdapter` will also write to and delete from all pools.
