<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Currency;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\Query\CurrencyProvider\CurrencyProviderQueryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider
 * @uses \SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 */
final class GetCurrencyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $query    = new GetCurrencyQuery($currency);

        $this->assertInstanceOf(CurrencyProviderQueryInterface::class, $query);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testConstructWithCurrencyObject(): void
    {
        $currency = new Currency('xts');
        $query    = new GetCurrencyQuery($currency);

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($currency->isEqualTo($output));
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testConstructWithCurrencyString(): void
    {
        $query = new GetCurrencyQuery('xts');

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertSame('XTS', $output->getCurrencyCode());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithInvalidValue(): void
    {
        $this->expectException(MoneyExceptionInterface::class);
        $query = new GetCurrencyQuery('1234');
    }

    /**
     * @covers ::queryFrom
     */
    public function testQueryFromWillThrowExceptionWhenCurrencyNotFound(): void
    {
        $query = new GetCurrencyQuery('usd');

        $this->expectException(MoneyExceptionInterface::class);
        $output = $query->queryFrom(new XCurrencyProvider());
    }
}
