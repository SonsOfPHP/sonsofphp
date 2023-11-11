<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery;
use SonsOfPHP\Contract\Money\Query\Money\MoneyQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery
 */
final class IsGreaterThanMoneyQueryTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsGreaterThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $query = new IsGreaterThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(200, Currency::USD())));
    }
}
