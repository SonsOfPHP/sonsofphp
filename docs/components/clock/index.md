---
title: Clock - Overview
---

The Clock Component is a wrapper around PHP's native DateTime objects and
functions. Using the Clock Component helps you test and keep everything a
standard timezone

## Installation

```shell
composer require sonsofphp/clock
```

## Usage

### SystemClock

The `SystemClock` is generally used in production. If you need check timestamps
or manage dates and times, this is the Clock you want to use. Use Dependency
Injection to make it easy to test with.

```php
<?php
use SonsOfPHP\Component\Clock\SystemClock;

$clock = new SystemClock();

$now = $clock->now(); // Returns a DateTimeImmutable object
```

### FixedClock

The `FixedClock` is used for testing. Just set the time and inject into your
class.

```php
<?php
use SonsOfPHP\Component\Clock\FixedClock;

$clock = new FixedClock();
$firstNow = $clock->now();
sleep(10);
$secondNow = $clock->now();

var_dump($firstNow === $secondNow); // true

// You can update the internal time at any time.
$clock->tick(); // Updates the internal clock to the curent time
$thridNow = $clock->now();

var_dump($firstNow === $secondNow); // still true
var_dump($firstNow === $thirdNow); // false
var_dump($thirdNow === $secondNow); // false

// You can also set the internal clock to whatever time you need it to be
$clock->tickTo('2022-04-20 04:20:00'); // format is "Y-m-d H:i:s"
```
