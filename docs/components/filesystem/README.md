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

// Write File
$filesystem->write('example.txt', 'Contents to write');

// Read File contents
$content = $filesystem->read('example.txt');

// Delete File
$filesystem->delete('example.txt');

// Check if file exists
$doesExist = $filesystem->exists('example.txt');

// Get MIME Type of file
$mimeType = $filesystem->mimeType('example.txt');

// Copy file
$filesystem->copy('source.txt', 'destination.txt');

// Move file
$filesystem->move('source.txt', 'destination.txt');
```

## Support for LiipImagineBundle

```shell
composer require sonsofphp/filesystem-liip-imagine
```

```yaml
# config/services.yaml
services:
    SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface:
        class: SonsOfPHP\Component\Filesystem\Adapter\NativeAdapter
        arguments: ['%kernel.project_dir%/var/data/%kernel.id%']
    SonsOfPHP\Contract\Filesystem\FilesystemInterface:
        class: SonsOfPHP\Component\Filesystem\Filesystem
        arguments: ['@SonsOfPHP\Contract\Filesystem\Adapter\AdapterInterface']
    imagine.cache.resolver.sonsofphp:
      class: SonsOfPHP\Bridge\LiipImagine\Filesystem\Imagine\Cache\Resolver\SonsOfPHPFilesystemResolver
      arguments:
        - '@SonsOfPHP\Contract\Filesystem\FilesystemInterface'
        - 'https://images.example.com'
      tags:
        - { name: "liip_imagine.cache.resolver", resolver: sonsofphp }
```

```yaml
# config/packages/liip_imagine.yaml
liip_imagine:
    data_loader: SonsOfPHP\Bridge\LiipImagine\Filesystem\Binary\Loader\SonsOfPHPFilesystemLoader
    cache: sonsofphp
```

## Need Help?

Check out [Sons of PHP's Organization Discussions][discussions].

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
