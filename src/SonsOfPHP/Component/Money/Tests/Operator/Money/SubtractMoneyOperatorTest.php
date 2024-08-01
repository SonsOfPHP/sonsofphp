<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;
use SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Contract\Money\MoneyOperatorInterface;

#[CoversClass(SubtractMoneyOperator::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(Money::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
#[UsesClass(SubtractAmountOperator::class)]
final class SubtractMoneyOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new SubtractMoneyOperator($money);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    public function testApplyWithSameCurrencies(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new SubtractMoneyOperator($money);

        $output = $operator->apply(Money::USD(300));

        $this->assertNotSame($money, $output);
        $this->assertSame('200', $output->getAmount()->toString());
    }

    public function testApplyWillThrowExceptionWhenCurrenciesAreDifferent(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new SubtractMoneyOperator(Money::JPY(1000));

        $this->expectException(MoneyException::class);
        $operator->apply($money);
    }
}
