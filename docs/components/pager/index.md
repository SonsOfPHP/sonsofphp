---
title: Pager
description: PHP Pager
---

Simple yet powerful pagination

## Installation

```shell
composer require sonsofphp/pager
```

## Usage

Basic Usage

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Pager\Pager;

$pager = new Pager(new ArrayAdapter($results));

foreach ($pager as $result) {
    // ...
}
```

Advance Usage

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;
use SonsOfPHP\Component\Pager\Pager;

// These are the default option values
$pager = new Pager(new ArrayAdapter($results), [
    'current_page' => 1,
    'max_per_page' => 10,
]);

// You can also set current page and max per page
$pager->setCurrentPage(1);
$pager->setMaxPerPage(10);


$totalPages   = $pager->getTotalPages();
$totalResults = $pager->getTotalResults();
$currentPage  = $pager->getCurrentPage();

if ($pager->haveToPaginate()) {
    // ...
}

if ($pager->hasPreviousPage()) {
    $prevPage = $pager->getPreviousPage();
    // ...
}

if ($pager->hasNextPage()) {
    $nextPage = $pager->getNextPage();
    // ...
}
```

## Adapters

### ArrayAdapter

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\ArrayAdapter;

$adapter = new ArrayAdapter($results);
```

### CallableAdabter

Will take any `callable` arguments.

```php
<?php

use SonsOfPHP\Component\Pager\Adapter\CallableAdapter;

$adapter = new CallableAdapter(
    count: function (): int {
        // ...
    },
    slice: function (int $offset, ?int $length): iterable {
        // ...
    },
);
```

### ArrayCollectionAdapter


!!! warning
    ```shell
    composer require sonsofphp/pager-doctrine-collections
    ```

```php
<?php

use Doctrine\Common\Collections\ArrayCollection;
use SonsOfPHP\Bridge\Doctrine\Collections\Pager\ArrayCollectionAdapter;

$collection = new ArrayCollection();

$adapter = new ArrayCollectionAdapter($collection);
```
