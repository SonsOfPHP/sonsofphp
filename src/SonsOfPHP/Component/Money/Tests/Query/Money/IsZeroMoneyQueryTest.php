<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery
 * @coversNothing
 */
#[CoversClass(IsZeroMoneyQuery::class)]
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

    public function testQuery(): void
    {
        $query = new IsZeroMoneyQuery();

        $this->assertTrue($query->queryFrom(new Money(0, Currency::USD())));
    }
}
