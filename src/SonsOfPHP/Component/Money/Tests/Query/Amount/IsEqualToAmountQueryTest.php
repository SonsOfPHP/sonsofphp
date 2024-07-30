<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @internal
 * @coversNothing
 */
#[CoversClass(IsEqualToAmountQuery::class)]
final class IsEqualToAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $query  = new IsEqualToAmountQuery($amount);

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $amount = new Amount(100);
        $query  = new IsEqualToAmountQuery($amount);

        $this->assertTrue($query->queryFrom($amount));
    }
}
