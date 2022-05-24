<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\AmountQueryInterface;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 */
final class IsGreaterThanOrEqualToAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query = new IsGreaterThanOrEqualToAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query = new IsGreaterThanOrEqualToAmountQuery($amount);

        $this->assertTrue($query->queryFrom($amount));
    }
}
