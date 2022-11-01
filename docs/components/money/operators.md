---
title: Operators - Money
---

Operators allow you to change something about the Money and will return a new
instance of the Money. For example, the methods: add, subtract, multiply, and
divide on the `Money` class are all MoneyOperators.

You can create your own Money Operators and use them with the Money class out of
the box.

```php
<?php
$operator = new MyOwnMoneyOperator();
$newMoney = $money->with($operator);
```

Here's how it works in slightly more detail.

```php
<?php
use SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator;
use SonsOfPHP\Component\Money\Money;

$accountBalance = Money::USD(1000);
$depositAmount  = Money::USD(2000);

// The construct takes the deposit amount we will apply to the balance
$operator = new AddMoneyOperator($depositAmount);

// The operator will apply (the deposit amount) to the account balance
// and the returned MoneyInterface will be a new object
$newBalance = $operator->apply($accountBalance);

$newBalance->isEqualTo($accountBalance); // will return false

// Another way to use the Operator is
$anotherNewBalance = $accountBalance->with($operator);

$newBalance->isEqualTo($anotherNewBalance); // will return true

// Even though $newBalance and $anotherNewBalance are different objects, they
// are both USD and both have the same amount (3000)
```

As you can see, using Operators provides you some power. You could create your
Operator to calculate a payment amount for a loan. Another great idea is to use
an Operator that can calculate tax.
