<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Component\Money\Query\Money\IsNegativeMoneyQuery;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

#[CoversClass(IsNegativeMoneyQuery::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Money::class)]
#[UsesClass(IsNegativeAmountQuery::class)]
final class IsNegativeMoneyQueryTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsNegativeMoneyQuery();

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $query = new IsNegativeMoneyQuery();

        $this->assertTrue($query->queryFrom(new Money(-10, Currency::USD())));
    }
}
