Sons of PHP - Clock Component
=============================

Lightweight implementation of a clock interface that can be used to abstract
datetime and makes it easier to test.

## Installation

```shell
composer require sonsofphp/clock
```

## Usage

```php
use SonsOfPHP\Component\Clock\SystemClock;
use DateTimeZone;

$clock = new SystemClock();

$timestamp = $clock->now(); // @var DateTimeInterface $timestamp

// To change the default Timezone from UTC to something else, pass a Timezone
// object
$est = new SystemClock(new DateTimeZone('America/New_York'));
```

### Test Clock Usage

```php
use SonsOfPHP\Component\Clock\TestClock;

$clock = new TestClock();

// It doesn't matter how many times you call "now()", it will return
// the same DateTimeInterface object
$timestamp = $clock->now(); // @var DateTimeInterface $timestamp

// To update the Test Clock, use "tick()" to update to the lastest time.
$clock->tick(); // Updates the internal clock with a DateTimeInterface object

// This will continue to return the same DateTimeInterface object until you
// call "tick()" again to refresh it.
$timestamp = $clock->now();

// To set it to a specific date and time, use "tickTo()". The format that needs
// to be passed in is YYYY-MM-DD HH:MM:SS
$clock->tickTo('2022-04-20 04:20:00');
$timestamp = $clock->now(); // DateTimeInterface set to April 20, 2022 4:20am
```
