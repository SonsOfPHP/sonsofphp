---
title: Http Factory
---

# Http Factory Component

Simple PSR-17 Http Factory Component

## Installation

```shell
composer require sonsofphp/http-factory
```

## Usage

```php
use SonsOfPHP\Component\HttpFactory\HttpFactory;

$factory = new HttpFactory();

$request       = $factory->createRequest($method, $uri);
$response      = $factory->createResponse();
$serverRequest = $factory->createServerRequest($method, $uri);
$stream        = $factory->createStream();
$stream        = $factory->createStreamFromFile('/path/to/file.ext');
$stream        = $factory->createStreamFromResource($resource);
$uploadedFile  = $factory->createUploadedFile($stream);
$uri           = $factory->createUri('https://docs.sonsofphp.com');
```

For more details, please see the source code or review the PSR-17 documentation.
