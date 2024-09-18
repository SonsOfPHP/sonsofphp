---
title: Cache Marshallers
---

# Marshallers

Using marshallers will allow you to serialize the cache values differently.

## SerializableMarshaller

This marshaller will use php's native `serialize` and `unserialize` functions on
the values.

```php
<?php

use SonsOfPHP\Componenet\Cache\Marshaller\SerializableMarshaller;

$marshaller = new SerializableMarshaller();
```

## JsonMarshaller

The json marshaller will use php's native `json_encode` and `json_decode`
functions to serialize data.

```php
<?php

use SonsOfPHP\Componenet\Cache\Marshaller\JsonMarshaller;

$marshaller = new JsonMarshaller();
```
