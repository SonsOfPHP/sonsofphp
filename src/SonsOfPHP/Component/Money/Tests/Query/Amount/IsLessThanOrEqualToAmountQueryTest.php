<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery;
use SonsOfPHP\Contract\Money\Query\Amount\AmountQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class IsLessThanOrEqualToAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query  = new IsLessThanOrEqualToAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsLessThanOrEqualToAmountQuery($amount);

        $this->assertTrue($query->queryFrom($amount));
    }
}
