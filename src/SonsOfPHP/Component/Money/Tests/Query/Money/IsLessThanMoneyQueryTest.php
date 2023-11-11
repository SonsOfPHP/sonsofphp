<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery;
use SonsOfPHP\Contract\Money\Query\Money\MoneyQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery
 */
final class IsLessThanMoneyQueryTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsLessThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $query = new IsLessThanMoneyQuery(new Money(100, Currency::USD()));

        $this->assertTrue($query->queryFrom(new Money(50, Currency::USD())));
    }
}
