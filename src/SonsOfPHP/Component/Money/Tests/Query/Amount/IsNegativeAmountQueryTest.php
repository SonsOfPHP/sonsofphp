<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Contract\Money\Query\Amount\AmountQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class IsNegativeAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsNegativeAmountQuery();

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(-100);
        $query  = new IsNegativeAmountQuery();

        $this->assertTrue($query->queryFrom($amount));
    }
}
