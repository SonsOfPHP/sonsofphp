<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

#[CoversClass(IsGreaterThanMoneyQuery::class)]
#[UsesClass(Money::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(IsEqualToAmountQuery::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
#[UsesClass(IsGreaterThanAmountQuery::class)]
final class IsGreaterThanMoneyQueryTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsGreaterThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $query = new IsGreaterThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(200, Currency::USD())));
    }

    public function testQueryThrowsExceptionWhenCurrencyIsDifferent(): void
    {
        $query = new IsGreaterThanMoneyQuery(new Money(100, Currency::USD()));

        $this->expectException(MoneyExceptionInterface::class);
        $query->queryFrom(new Money(200, Currency::EUR()));
    }
}
