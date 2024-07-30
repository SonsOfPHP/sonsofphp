<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Formatter;

use NumberFormatter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Formatter\IntlMoneyFormatter;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyFormatterInterface;

#[RequiresPhpExtension('intl')]
#[CoversClass(IntlMoneyFormatter::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Money::class)]
final class IntlMoneyFormatterTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $formatter = new IntlMoneyFormatter(new NumberFormatter('en_US', NumberFormatter::CURRENCY));

        $this->assertInstanceOf(MoneyFormatterInterface::class, $formatter);
    }

    public function testFormat(): void
    {
        $formatter = new IntlMoneyFormatter(new NumberFormatter('en_US', NumberFormatter::CURRENCY));

        $this->assertSame('$4.20', $formatter->format(Money::USD(4.20)));
    }
}
