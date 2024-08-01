<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\CurrencyProvider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyProviderQueryInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

#[CoversClass(HasCurrencyQuery::class)]
#[UsesClass(Currency::class)]
#[UsesClass(XCurrencyProvider::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
final class HasCurrencyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $query    = new HasCurrencyQuery($currency);

        $this->assertInstanceOf(CurrencyProviderQueryInterface::class, $query);
    }

    public function testConstructWithCurrencyObject(): void
    {
        $currency = new Currency('xts');
        $query    = new HasCurrencyQuery($currency);

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($output);
    }

    public function testConstructWithCurrencyString(): void
    {
        $query = new HasCurrencyQuery('xts');

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($output);
    }

    public function testConstructWithInvalidValue(): void
    {
        $this->expectException(MoneyExceptionInterface::class);
        $query = new HasCurrencyQuery('1234');
    }

    public function testQueryFromWhenProviderDoesNotContainCurrency(): void
    {
        $query = new HasCurrencyQuery('usd');

        $output = $query->queryFrom(new XCurrencyProvider());
        $this->assertFalse($output);
    }
}
