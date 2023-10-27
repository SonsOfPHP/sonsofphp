---
title: Filesystem
---

# Filesystem Component

This filesystem is more of a virtual filesystem that allows you to keep one
interface but is able to manage files on multiple backends. For example, you
could use the `NativeAdapter` to write files to disk and during testing, you can
use the `NullAdapter` so no files are actually written to disk.

## Installation

```shell
composer require sonsofphp/filesystem
```

## Usage

```php
<?php

use SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter;
use SonsOfPHP\Component\Filesystem\Filesystem;

$filesystem = new Filesystem(new NativeAdapter('/tmp'));
```

## Need Help?

Check out [Sons of PHP's Organization Discussions][discussions].

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
