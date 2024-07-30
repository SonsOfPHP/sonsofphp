<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @internal
 * @coversNothing
 */
#[CoversClass(IsGreaterThanAmountQuery::class)]
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

    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsGreaterThanAmountQuery(new Amount(10));

        $this->assertTrue($query->queryFrom($amount));
    }
}
