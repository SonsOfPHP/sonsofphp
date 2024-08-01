<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Contract\Money\CurrencyInterface;

#[CoversClass(Currency::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
final class CurrencyTest extends TestCase
{
    public function testContructWillValidateCurrencyCode(): void
    {
        $currency = new Currency('usd');

        $this->assertSame('USD', $currency->getCurrencyCode());
    }

    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $this->assertInstanceOf(CurrencyInterface::class, $currency);

        $currency = Currency::USD();
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
    }

    public function testMagicFactory(): void
    {
        $currency = Currency::USD();
        $this->assertSame('USD', $currency->getCurrencyCode());
    }

    public function testDefaults(): void
    {
        $currency = Currency::USD();
        $this->assertNull($currency->getNumericCode());
        $this->assertNull($currency->getMinorUnit());
    }

    public function testToStringMagicMethod(): void
    {
        $currency = Currency::USD();

        $this->assertSame('USD', (string) $currency);
    }

    public function testIsEqualTo(): void
    {
        $usd   = Currency::USD();
        $other = Currency::USD();
        $jpy   = Currency::JPY();

        $this->assertFalse($usd->isEqualTo($jpy));
        $this->assertNotSame($usd, $other);
        $this->assertTrue($usd->isEqualTo($other));
    }
}
