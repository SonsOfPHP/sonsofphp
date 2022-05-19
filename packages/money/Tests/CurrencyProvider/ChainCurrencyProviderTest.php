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
 */
final class ChainCurrencyProviderTest extends TestCase
{
    private $provider;

    protected function setUp(): void
    {
        $this->provider = new ChainCurrencyProvider([
            new CurrencyProvider(),
            new XCurrencyProvider(),
        ]);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $provider = new ChainCurrencyProvider();
        $this->assertInstanceOf(CurrencyProviderInterface::class, $provider);
    }

    public function testGetCurrencies(): void
    {
        foreach ($this->provider->getCurrencies() as $currency) {
            $this->assertInstanceOf(CurrencyInterface::class, $currency);
            $this->assertNotNull($currency->getNumericCode());
            $this->assertNotNull($currency->getMinorUnit());
        }
    }

    public function testHasCurrencyWithString(): void
    {
        $this->assertTrue($this->provider->hasCurrency('usd')); // CurrencyProvider
        $this->assertTrue($this->provider->hasCurrency('xts')); // XCurrencyProvider

        $this->assertFalse($this->provider->hasCurrency('zzz'));
    }

    public function testHasCurrencyWithCurrencyObject(): void
    {
        $this->assertTrue($this->provider->hasCurrency(Currency::USD()));

        $this->assertFalse($this->provider->hasCurrency('zzz'));
    }

    public function testHasCurrencyWithValidUnknowString(): void
    {
        $this->assertFalse($this->provider->hasCurrency('zzz'));
    }

    public function testHasCurrencyWithInvalidInput(): void
    {
        $this->expectException(MoneyException::class);
        $this->assertFalse($this->provider->hasCurrency('xxxxxx'));
    }

    public function testGetCurrencyWithString(): void
    {
        $currency = $this->provider->getCurrency('usd');
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    public function testGetCurrencyWithObject(): void
    {
        $currency = $this->provider->getCurrency(Currency::USD());
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    public function testGetCurrencyWithUnknowCurrency(): void
    {
        $this->expectException(MoneyException::class);
        $this->provider->getCurrency('zzz');
    }

    public function testGetCurrencyWithValueError(): void
    {
        $this->expectException(MoneyException::class);
        $this->provider->getCurrency('xxxxxxxx');
    }
}
