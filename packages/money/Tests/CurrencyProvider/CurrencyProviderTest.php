<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\CurrencyProvider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProviderInterface;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider
 */
final class CurrencyProviderTest extends TestCase
{
    /**
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new CurrencyProvider();
        $this->assertInstanceOf(CurrencyProviderInterface::class, $provider);
    }

    /**
     * @covers ::getCurrencies
     */
    public function testGetCurrencies(): void
    {
        $provider = new CurrencyProvider();

        foreach ($provider->getCurrencies() as $currency) {
            $this->assertInstanceOf(CurrencyInterface::class, $currency);
            $this->assertNotNull($currency->getNumericCode());
            $this->assertNotNull($currency->getMinorUnit());
        }
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithString(): void
    {
        $provider = new CurrencyProvider();

        $this->assertTrue($provider->hasCurrency('usd'));

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithCurrencyObject(): void
    {
        $provider = new CurrencyProvider();

        $this->assertTrue($provider->hasCurrency(Currency::USD()));

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithValidUnknowString(): void
    {
        $provider = new CurrencyProvider();

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithInvalidInput(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyException::class);
        $this->assertFalse($provider->hasCurrency('xxxxxx'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithString(): void
    {
        $provider = new CurrencyProvider();

        $currency = $provider->getCurrency('usd');
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithObject(): void
    {
        $provider = new CurrencyProvider();

        $currency = $provider->getCurrency(Currency::USD());
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithUnknowCurrency(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyException::class);
        $provider->getCurrency('xxx');
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithValueError(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyException::class);
        $provider->getCurrency('xxxxxxxx');
    }
}
