Sons of PHP - Money Component
======================================

Money Abstraction for projects working with money and currencies.

## Installation

```shell
composer require sonsofphp/money
```

## Usage

```php
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\CUrrency;

$money = new Money(100, new Currency('USD'));
```
