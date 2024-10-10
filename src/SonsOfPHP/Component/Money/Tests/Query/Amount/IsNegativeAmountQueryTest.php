<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Contract\Money\AmountQueryInterface;

#[CoversClass(IsNegativeAmountQuery::class)]
#[UsesClass(Amount::class)]
final class IsNegativeAmountQueryTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $query = new IsNegativeAmountQuery();

        $this->assertInstanceOf(AmountQueryInterface::class, $query);
    }

    public function testQuery(): void
    {
        $amount = new Amount(-100);
        $query  = new IsNegativeAmountQuery();

        $this->assertTrue($query->queryFrom($amount));
    }
}
