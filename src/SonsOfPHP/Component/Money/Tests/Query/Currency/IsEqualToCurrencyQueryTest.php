<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Currency;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyQueryInterface;

#[CoversClass(IsEqualToCurrencyQuery::class)]
#[UsesClass(Currency::class)]
final class IsEqualToCurrencyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $query    = new IsEqualToCurrencyQuery($currency);

        $this->assertInstanceOf(CurrencyQueryInterface::class, $query);
    }

    public function testQueryFrom(): void
    {
        $currency = new Currency('usd');
        $query    = new IsEqualToCurrencyQuery($currency);

        $this->assertTrue($query->queryFrom($currency));
    }
}
