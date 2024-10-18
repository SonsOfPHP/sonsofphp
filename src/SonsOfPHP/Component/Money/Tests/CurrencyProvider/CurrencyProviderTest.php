<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\CurrencyProvider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\AbstractCurrencyProvider;
use SonsOfPHP\Component\Money\CurrencyProvider\CurrencyProvider;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\GetCurrencyQuery;
use SonsOfPHP\Component\Money\Query\CurrencyProvider\HasCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\CurrencyProviderInterface;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;

#[CoversClass(CurrencyProvider::class)]
#[UsesClass(GetCurrencyQuery::class)]
#[UsesClass(HasCurrencyQuery::class)]
#[UsesClass(AbstractCurrencyProvider::class)]
#[UsesClass(Currency::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
final class CurrencyProviderTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new CurrencyProvider();
        $this->assertInstanceOf(CurrencyProviderInterface::class, $provider);
    }

    public function testGetCurrencies(): void
    {
        $provider = new CurrencyProvider();

        foreach ($provider->getCurrencies() as $currency) {
            $this->assertInstanceOf(CurrencyInterface::class, $currency);
            $this->assertNotNull($currency->getNumericCode());
            $this->assertNotNull($currency->getMinorUnit());
        }
    }

    public function testHasCurrencyWithString(): void
    {
        $provider = new CurrencyProvider();

        $this->assertTrue($provider->hasCurrency('usd'));

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    public function testHasCurrencyWithCurrencyObject(): void
    {
        $provider = new CurrencyProvider();

        $this->assertTrue($provider->hasCurrency(Currency::USD()));

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    public function testHasCurrencyWithValidUnknowString(): void
    {
        $provider = new CurrencyProvider();

        $this->assertFalse($provider->hasCurrency('xxx'));
    }

    public function testHasCurrencyWithInvalidInput(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyExceptionInterface::class);
        $this->assertFalse($provider->hasCurrency('xxxxxx'));
    }

    public function testGetCurrencyWithString(): void
    {
        $provider = new CurrencyProvider();

        $currency = $provider->getCurrency('usd');
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    public function testGetCurrencyWithObject(): void
    {
        $provider = new CurrencyProvider();

        $currency = $provider->getCurrency(Currency::USD());
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
        $this->assertSame(840, $currency->getNumericCode());
        $this->assertSame(2, $currency->getMinorUnit());
    }

    public function testGetCurrencyWithUnknowCurrency(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyExceptionInterface::class);
        $provider->getCurrency('xxx');
    }

    public function testGetCurrencyWithValueError(): void
    {
        $provider = new CurrencyProvider();

        $this->expectException(MoneyExceptionInterface::class);
        $provider->getCurrency('xxxxxxxx');
    }
}
