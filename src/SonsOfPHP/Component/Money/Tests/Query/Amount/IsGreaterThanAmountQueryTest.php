<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\AmountQueryInterface;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class IsGreaterThanAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query  = new IsGreaterThanAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsGreaterThanAmountQuery(new Amount(10));

        $this->assertTrue($query->queryFrom($amount));
    }
}
