<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Query\Money\IsPositiveMoneyQuery;
use SonsOfPHP\Contract\Money\MoneyQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsPositiveMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery
 * @coversNothing
 */
#[CoversClass(IsPositiveMoneyQuery::class)]
final class IsPositiveMoneyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsPositiveMoneyQuery();

        $this->assertInstanceOf(MoneyQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $query = new IsPositiveMoneyQuery();

        $this->assertTrue($query->queryFrom(new Money(10, Currency::USD())));
    }
}
