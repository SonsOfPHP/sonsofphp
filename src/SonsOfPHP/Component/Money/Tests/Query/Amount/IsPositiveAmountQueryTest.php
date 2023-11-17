<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class IsPositiveAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsPositiveAmountQuery();

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    /**
     * @covers ::queryFrom
     */
    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsPositiveAmountQuery();

        $this->assertTrue($query->queryFrom($amount));
    }
}
