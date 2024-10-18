<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator;
use SonsOfPHP\Contract\Money\MoneyOperatorInterface;

#[CoversClass(MultiplyMoneyOperator::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Money::class)]
#[UsesClass(MultiplyAmountOperator::class)]
final class MultiplyMoneyOperatorTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new MultiplyMoneyOperator(20);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    public function testApplyWithSameCurrencies(): void
    {
        $money    = new Money(5, new Currency('usd'));
        $operator = new MultiplyMoneyOperator(20);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('100', $output->getAmount()->toString());
    }
}
