<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\CurrencyProvider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyProviderQueryInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

#[CoversClass(GetCurrencyQuery::class)]
#[UsesClass(Currency::class)]
#[UsesClass(XCurrencyProvider::class)]
#[UsesClass(GetCurrencyQuery::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
final class GetCurrencyQueryTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $query    = new GetCurrencyQuery($currency);

        $this->assertInstanceOf(CurrencyProviderQueryInterface::class, $query);
    }

    public function testConstructWithCurrencyObject(): void
    {
        $currency = new Currency('xts');
        $query    = new GetCurrencyQuery($currency);

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($currency->isEqualTo($output));
    }

    public function testConstructWithCurrencyString(): void
    {
        $query = new GetCurrencyQuery('xts');

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertSame('XTS', $output->getCurrencyCode());
    }

    public function testConstructWithInvalidValue(): void
    {
        $this->expectException(MoneyExceptionInterface::class);
        new GetCurrencyQuery('1234');
    }

    public function testQueryFromWillThrowExceptionWhenCurrencyNotFound(): void
    {
        $query = new GetCurrencyQuery('usd');

        $this->expectException(MoneyExceptionInterface::class);
        $query->queryFrom(new XCurrencyProvider());
    }
}
