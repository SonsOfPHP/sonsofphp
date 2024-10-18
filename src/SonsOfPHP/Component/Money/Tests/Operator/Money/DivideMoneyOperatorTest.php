<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator;
use SonsOfPHP\Contract\Money\MoneyOperatorInterface;

#[CoversClass(DivideMoneyOperator::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Money::class)]
#[UsesClass(DivideAmountOperator::class)]
final class DivideMoneyOperatorTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new DivideMoneyOperator(5);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    public function testApplyWithSameCurrencies(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new DivideMoneyOperator(5);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('20', $output->getAmount()->toString());
    }
}
