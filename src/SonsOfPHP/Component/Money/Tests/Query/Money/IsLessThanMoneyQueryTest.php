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
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

#[CoversClass(IsLessThanMoneyQuery::class)]
#[UsesClass(Money::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(IsEqualToAmountQuery::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
#[UsesClass(IsLessThanAmountQuery::class)]
final class IsLessThanMoneyQueryTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsLessThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $query = new IsLessThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(50, Currency::USD())));
    }

    public function testQueryFromThrowsExceptionWhenCurrencyIsDifferent(): void
    {
        $query = new IsLessThanMoneyQuery(new Money(100, Currency::USD()));

        $this->expectException(MoneyExceptionInterface::class);
        $query->queryFrom(new Money(200, Currency::EUR()));
    }
}
