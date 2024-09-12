---
title: Assert
---

# Assert

## Installation

```shell
composer require sonsofphp/assert
```

## Usage

```php
<?php

use SonsOfPHP\Component\Assert\Assert;
use SonsOfPHP\Component\Assert\InvalidArgumentException;

$data = 'test';
Assert::string($data);

// @throws InvalidArgumentException
Assert::int($data);

// Disable exceptions
Assert::disable();

// @return bool
Assert::int($data);

// Re-enable exceptions
Assert::enable();
```

## Assertions

```php
Assert::array($value);
Assert::bool($value);
Assert::callable($value);
Assert::empty($value);
Assert::eq($value, $value2);
Assert::false($value);
Assert::float($value);
Assert::int($value);
Assert::null($value);
Assert::numeric($value);
Assert::object($value);
Assert::resource($value);
Assert::same($value, $value2);
Assert::scalar($value);
Assert::string($value);
Assert::true($value);
```

## Magic Assertions

```php
// "all"
// Throws exception if any value in $values is not the correct type
Assert::allString($values);
Assert::allString(['one', 'two', 3]); // Throws Exception

// "not"
// Throws exception if $value is the type
Assert::notString($value);
Assert::notString('opps'); // Throws Exception

// "nullOr"
// Throws exception if $value is not null or not type
Assert::nullOrString($value);
Assert::nullOrString(42); // Throws Exception
```
