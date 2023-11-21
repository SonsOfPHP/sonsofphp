<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery
 */
final class IsLessThanOrEqualToMoneyQueryTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(10, Currency::USD())));
    }

    /**
     * @covers ::queryFrom
     */
    public function testQueryFromThrowsExceptionWhenCurrencyIsDifferent(): void
    {
        $query = new IsLessThanOrEqualToMoneyQuery(new Money(100, Currency::USD()));

        $this->expectException(MoneyExceptionInterface::class);
        $query->queryFrom(new Money(200, Currency::EUR()));
    }
}
