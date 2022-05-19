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
    /**
     * @covers ::__construct
     * @covers ::__callStatic
     */
    public function testItHasTheCorrectInterface(): void
    {
        $currency = new Currency('usd');
        $this->assertInstanceOf(CurrencyInterface::class, $currency);

        $currency = Currency::USD();
        $this->assertInstanceOf(CurrencyInterface::class, $currency);
    }

    /**
     * @covers ::__callStatic
     * @covers ::getCurrencyCode
     */
    public function testMagicFactory(): void
    {
        $currency = Currency::USD();
        $this->assertSame('USD', $currency->getCurrencyCode());
    }

    /**
     * @covers ::getNumericCode
     * @covers ::getMinorUnit
     */
    public function testDefaults(): void
    {
        $currency = Currency::USD();
        $this->assertNull($currency->getNumericCode());
        $this->assertNull($currency->getMinorUnit());
    }

    /**
     * @covers ::__toString
     */
    public function testToStringMagicMethod(): void
    {
        $currency = Currency::USD();

        $this->assertSame('USD', (string) $currency);
    }

    /**
     * @covers ::isEqualTo
     * @covers ::query
     */
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
