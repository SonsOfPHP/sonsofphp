<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Formatter\IntlMoneyFormatter;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyFormatterInterface;

/**
 * @requires extension intl
 *
 * @coversDefaultClass \SonsOfPHP\Component\Money\Formatter\IntlMoneyFormatter
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Formatter\IntlMoneyFormatter
 * @uses \SonsOfPHP\Component\Money\Money
 */
final class IntlMoneyFormatterTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $formatter = new IntlMoneyFormatter(new \NumberFormatter('en_US', \NumberFormatter::CURRENCY));

        $this->assertInstanceOf(MoneyFormatterInterface::class, $formatter);
    }

    /**
     * @covers ::format
     */
    public function testFormat(): void
    {
        $formatter = new IntlMoneyFormatter(new \NumberFormatter('en_US', \NumberFormatter::CURRENCY));

        $this->assertSame('$4.20', $formatter->format(Money::USD(4.20)));
    }
}
