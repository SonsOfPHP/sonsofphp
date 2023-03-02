<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\CurrencyProvider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyInterface;
use SonsOfPHP\Component\Money\CurrencyProvider\ChainCurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProviderInterface;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Component\Money\Exception\MoneyException;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\CurrencyProvider\ChainCurrencyProvider
 *
 * @internal
 */
final class ChainCurrencyProviderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new ChainCurrencyProvider();
        $this->assertInstanceOf(CurrencyProviderInterface::class, $provider);
    }

    /**
     * @covers ::__construct
     * @covers ::addProvider
     */
    public function testPassingInProvidersViaConstructWillAddProviders(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->assertGreaterThan(0, iterator_count($provider->getCurrencies()));
    }

    /**
     * @covers ::getCurrencies
     */
    public function testGetCurrencies(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

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
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->assertTrue($provider->hasCurrency('usd')); // CurrencyProvider
        $this->assertTrue($provider->hasCurrency('xts')); // XCurrencyProvider

        $this->assertFalse($provider->hasCurrency('zzz'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithCurrencyObject(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->assertTrue($provider->hasCurrency(Currency::USD()));

        $this->assertFalse($provider->hasCurrency('zzz'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithValidUnknowString(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->assertFalse($provider->hasCurrency('zzz'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::hasCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testHasCurrencyWithInvalidInput(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->expectException(MoneyException::class);
        $this->assertFalse($provider->hasCurrency('xxxxxx'));
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithString(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

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
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

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
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->expectException(MoneyException::class);
        $provider->getCurrency('zzz');
    }

    /**
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::getCurrency
     * @covers \SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider::query
     */
    public function testGetCurrencyWithValueError(): void
    {
        $provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);

        $this->expectException(MoneyException::class);
        $provider->getCurrency('xxxxxxxx');
    }
}
