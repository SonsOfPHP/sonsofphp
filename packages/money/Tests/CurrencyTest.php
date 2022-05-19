<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Currency
 */
final class CurrencyTest extends TestCase
{
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
}
