<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\CurrencyProvider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\CurrencyProvider\XCurrencyProvider;
use SonsOfPHP\Contract\Money\CurrencyInterface;
use SonsOfPHP\Contract\Money\CurrencyProviderInterface;

#[CoversClass(XCurrencyProvider::class)]
#[UsesClass(Currency::class)]
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
