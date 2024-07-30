<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @internal
 * @coversNothing
 */
#[CoversClass(IsGreaterThanOrEqualToAmountQuery::class)]
final class IsGreaterThanOrEqualToAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query  = new IsGreaterThanOrEqualToAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsGreaterThanOrEqualToAmountQuery($amount);

        $this->assertTrue($query->queryFrom($amount));
    }
}
