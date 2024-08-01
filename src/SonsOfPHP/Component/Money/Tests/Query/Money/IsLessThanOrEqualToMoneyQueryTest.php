<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

#[CoversClass(IsLessThanOrEqualToMoneyQuery::class)]
#[UsesClass(Money::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Amount::class)]
#[UsesClass(IsLessThanOrEqualToAmountQuery::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
final class IsLessThanOrEqualToMoneyQueryTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(10, Currency::USD())));
    }

    public function testQueryFromThrowsExceptionWhenCurrencyIsDifferent(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->expectException(MoneyExceptionInterface::class);
        $query->queryFrom(new Money(200, Currency::EUR()));
    }
}
