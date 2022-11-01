---
title: Money
---

# Money Component

The Money component was inspired by JSR 354 along with a few other ideas. It is
mainly used for services and sites dealing with money.

## Installation

```shell
composer require sonsofphp/money
```

## Usage

### Money

```php
<?php
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Currency;

// Can use the Money Class like this
$money = new Money(100, new Currency('USD'));
$amount = $money->getAmount(); // AmountInterface
$value = $amount->toString(); // `toInt` and `toFloat` are also supported

// Or like this
$money = Money::USD(100);
```

You can preform different [operations](operators.md) to create new money.

```php
<?php
use SonsOfPHP\Component\Money\Money;

$money = Money::USD(100);

$newMoney1 = $money->add(Money::USD(100)); // Amount is now 200
$newMoney2 = $money->subtract(Money::USD(100)); // Amount is now 0

// The amount of the orginal Money does not change
$amount = $money->getAmount(); // Amount is 100
```

You can multiple and divide too.

```php
<?php
use SonsOfPHP\Component\Money\Money;

$money = Money::USD(100);

$newMoney1 = $money->multiply(5); // value of Amount is now 500
$newMoney2 = $money->divide(5); // value of Amount is now 20

// The amount of the orginal Money does not change
$amount = $money->getAmount(); // value of Amount is 100
```

### Currency

```php
<?php
use SonsOfPHP\Component\Money\Currency;

$currency = new Currency('USD');
// OR
$currency = Currency::USD();
```

## Need Help?

Check out [Sons of PHP's Organization Discussions][discussions].

[discussions]: https://github.com/orgs/SonsOfPHP/discussions
