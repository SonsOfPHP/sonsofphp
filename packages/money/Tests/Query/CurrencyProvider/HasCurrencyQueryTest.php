<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Query\Currency;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\CurrencyProviderQueryInterface;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery
 *
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider
 */
final class HasCurrencyQueryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $query = new HasCurrencyQuery($currency);

        $this->assertInstanceOf(CurrencyProviderQueryInterface::class, $query);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testConstructWithCurrencyObject(): void
    {
        $currency = new Currency('xts');
        $query = new HasCurrencyQuery($currency);

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($output);
    }

    /**
     * @covers ::__construct
     * @covers ::queryFrom
     */
    public function testConstructWithCurrencyString(): void
    {
        $query = new HasCurrencyQuery('xts');

        $output = $query->queryFrom(new XCurrencyProvider());

        $this->assertTrue($output);
    }

    /**
     * @covers ::__construct
     */
    public function testConstructWithInvalidValue(): void
    {
        $this->expectException(MoneyException::class);
        $query = new HasCurrencyQuery('1234');
    }

    /**
     * @covers ::queryFrom
     */
    public function testQueryFromWhenProviderDoesNotContainCurrency(): void
    {
        $query = new HasCurrencyQuery('usd');

        $output = $query->queryFrom(new XCurrencyProvider());
        $this->assertFalse($output);
    }
}
