---
title: Pager
description: Simple yet powerful pagination
---

# Pager

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
