---
title: Currency Provider - Money
---

# Currency Providers

The Currency Provider allows you to grab all the known Currencies. It also has
a `query` method to pass in `CurrencyProviderQuery`s to return various results.

## Basic Usage

### Loop over Currencies

```php
<?php
// @var CurrencyInterface $currency
foreach ($provider->getCurrencies() as $currency) {
    // Do something with each currency
}
```

### Check if Provider has a Currency

```php
// Check to see if the provider has the currency you are looking for
// @var bool $hasCurrency
$hasCurrency = $provider->hasCurrency('USD');
$hasCurrency = $provider->hasCurrency(Currency::USD());
```

### Getting Currency from the Provider

```php
// You can get a Currency like this. If the Currency does not exist
// it will throw an exception.
// @var CurrencyInterface $currency
$currency = $provider->getCurrency('USD');

$code      = $currency->getCurrencyCode(); // "USD"
$numCode   = $currency->getNumericCode(); // 840
$minorUnit = $currency->getMinorUnit(); // 2
```

## Currency Providers

### CurrencyProvider

The `CurrencyProvider` is usually the one that you will be using most of the
time. It provides most of the [ISO 4217][iso-4217] currencies.

```php
<?php
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider;

$provider = new CurrencyProvider();
```

### XCurrencyProvider

The `XCurrencyProvider` gives you access to all of the "X" Currencies. For
example, this provider is where you will find the Currency "XTS" which is
reserved for use in testing.

```php
<?php
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;

$provider = new XCurrencyProvider();
```

### ChainCurrencyProvider

The `ChainCurrencyProvider` lets you use multiple Currency Providers together at
once. It does not provide any currencies itself.

```php
<?php
use SonsOfPHP\Component\Money\CurrencyProvider\ChainCurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;

$provider = new ChainCurrencyProvider([
    new CurrencyProvider(),
    new XCurrencyProvider(),
]);

// You can add additional providers as well
$provider->addProvier(new MyCustomCurrencyProvider());
```

[iso-4217]: https://en.wikipedia.org/wiki/ISO_4217
