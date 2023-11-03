<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator
 */
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

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWithSameCurrencies(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new SubtractMoneyOperator($money);

        $output = $operator->apply(Money::USD(300));

        $this->assertNotSame($money, $output);
        $this->assertSame('200', $output->getAmount()->toString());
    }

    /**
     * @covers ::apply
     */
    public function testApplyWillThrowExceptionWhenCurrenciesAreDifferent(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new SubtractMoneyOperator(Money::JPY(1000));

        $this->expectException(MoneyException::class);
        $operator->apply($money);
    }
}
