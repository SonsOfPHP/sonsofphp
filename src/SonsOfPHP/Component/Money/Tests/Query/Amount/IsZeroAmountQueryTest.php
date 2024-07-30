<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @internal
 * @coversNothing
 */
#[CoversClass(IsZeroAmountQuery::class)]
final class IsZeroAmountQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsZeroAmountQuery();

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $amount = new Amount(0);
        $query  = new IsZeroAmountQuery();

        $this->assertTrue($query->queryFrom($amount));
    }
}
