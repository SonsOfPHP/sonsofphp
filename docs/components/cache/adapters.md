---
title: Cache Adapters
---

# Adapters

All adapters implement the PSR-6 `\Psr\Cache\CacheItemPoolInterface` and can be used as
standalone cache pools.

## ApcuAdapter

Requires the APCu extension is loaded and enabled.

```php
<?php

use SonsOfPHP\Componenet\Cache\Adapter\ApcuAdapter;

$cache = new ApcuAdapter();
```

## ArrayAdapter

Stores cache items in an internal PHP array.

```php
<?php

use SonsOfPHP\Componenet\Cache\Adapter\ArrayAdapter;

$cache = new ArrayAdapter();
```

## ChainAdapter

The Chain Adapter will take one or more adapters. It will WRITE to all adapters
and will READ from each adapter until it finds a hit and return that cache item.

```php
<?php

use SonsOfPHP\Componenet\Cache\Adapter\ApcuAdapter;
use SonsOfPHP\Componenet\Cache\Adapter\ArrayAdapter;
use SonsOfPHP\Componenet\Cache\Adapter\ChainAdapter;

$cache = new ChainAdapter([
    new ArrayAdapter(),
    new ApcuAdapter(),
]);
```

## NullAdapter

Mainly used for testing, however you could have some checks in place and if
those checks fail, you could fallback to this adapter.

```php
<?php

use SonsOfPHP\Componenet\Cache\Adapter\NullAdapter;

$cache = new NullAdapter();
```
