<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\AmountQueryInterface;
use SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 */
final class IsZeroAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query = new IsZeroAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(0);
        $query = new IsZeroAmountQuery();

        $this->assertTrue($query->queryFrom($amount));
    }
}
