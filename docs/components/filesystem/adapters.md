---
title: Filesystem - Adapters
---

# Adapters

Adapters allow you to connector various services such as Amazon S3 for storage.

## Standard Adapters

### InMemoryAdapter

In Memory Adapter does not write anything to disk but keeps them in memory.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;

$adapter = new InMemoryAdapter();
```

### NativeAdapter

Read/write files to disk.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;

$adapter = new NativeAdapter(
    prefix: '/tmp',
);
```

### NullAdapter

This adapter does not read or write anything. Mainly used for testing.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\NullAdapter;

$adapter = new NullAdapter();
```

## Special Adapters

Special adapters are used in various use cases.

### ChainAdapter

This allows you to use multiple adapters together.

When writing a file, it will write to ALL adapters.

When getting a file, it will return the file on the first adapter that has that
file.

When deleting a file, it will delete them from ALL adapters.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\ChainAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\InMemoryAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;

$adapter = new ChainAdapter([
    new InMemoryAdapter(),
    new NativeAdater('/tmp'),
]);
```

### ReadOnlyAdapter

This ONLY allows you to read files from a filesystem. You cannot write anything.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\ReadOnlyAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;

$adapter = new ReadOnlyAdapter(new NativeAdater('/tmp'));
```

### WormAdapter

This adapters allows you to Write Once, Read Many (WORM). Once a file has been
written, it CANNOT be modified.

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\WormAdapter;
use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;

$adapter = new WormAdapter(new NativeAdater('/tmp'));
```

## Additional Adapters

### AWS S3

```shell
composer require sonsofphp/filesystem-aws
```

```php
<?php

use SonsOfPHP\Bridge\Aws\Filesystem\Adapter\S3Adapter;

$adapter = new S3Adapter($s3Client, 'bucket-name');
```
