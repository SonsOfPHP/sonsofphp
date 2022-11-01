---
title: Queries - Money
---

Queries are used to answer questions about the Money. They can return different
values based on what you want.

You can also create and use your own money queries.

```php
<?php
$query = new DummyQuery();
$result = $money->query($query);
```

Let's take a look a `MoneyQuery` in a little more detail.

```php
<?php
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Currency;

$currency    = Currency::USD();
$currencyUSD = Currency::USD();
$currencyJPY = Currency::JPY();

// We create the new query and inject the dependencies
$query = new IsEqualToCurrencyQuery($currency);

// Both currencies have the same Currency Code, so we can say
// they are equal
$isEqual = $query->queryFrom($currencyUSD); // returns true

// Another way to do the same thing is
$isEqual = $currencyUSD->query($query); // returns true
$isEqual = $currencyJPY->query($query); // returns false
```

Queries aren't just limited to `bool` type of results. Running a query can return
any type of result. This means you could return an `array` or even a
`Generator`.
