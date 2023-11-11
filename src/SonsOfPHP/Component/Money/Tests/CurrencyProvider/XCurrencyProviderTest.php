<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\CurrencyProvider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\CurrencyProviderInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider
 *
 * @uses \SonsOfPHP\Component\Money\Currency
 */
final class XCurrencyProviderTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new XCurrencyProvider();
        $this->assertInstanceOf(CurrencyProviderInterface::class, $provider);
    }

    /**
     * @covers ::getCurrencies
     */
    public function testGetCurrencies(): void
    {
        $provider = new XCurrencyProvider();

        foreach ($provider->getCurrencies() as $currency) {
            $this->assertInstanceOf(CurrencyInterface::class, $currency);
            $this->assertNotNull($currency->getNumericCode());
            $this->assertNotNull($currency->getMinorUnit());
        }
    }
}
