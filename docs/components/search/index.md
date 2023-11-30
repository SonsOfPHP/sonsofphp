---
title: Search
description: Abstract PHP Search Library that supports many different backends
---

## Installation

```shell
composer require sonsofphp/search
```

## Usage

```php
<?php

use SonsOfPHP\Component\Search\Backend\ElasticBackend;
use SonsOfPHP\Component\Search\Search;
use SonsOfPHP\Component\Search\Query;

$search = new Search(new ElasticBackend(/*...*/));
$query  = new Query();
// ...
$pager = $search->query($query); // @var \SonsOfPHP\Contract\Pager\PagerInterface
foreach ($pager as $result) {
    // ...
}
```
