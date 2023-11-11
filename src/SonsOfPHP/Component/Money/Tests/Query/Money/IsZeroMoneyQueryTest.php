<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery;
use SonsOfPHP\Contract\Money\Query\Money\MoneyQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery
 */
final class IsZeroMoneyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsZeroMoneyQuery();

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $query = new IsZeroMoneyQuery();

        $this->assertTrue($query->queryFrom(new Money(0, Currency::USD())));
    }
}
