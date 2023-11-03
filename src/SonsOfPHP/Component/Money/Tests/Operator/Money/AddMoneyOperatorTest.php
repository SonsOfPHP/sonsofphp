<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator
 */
final class AddMoneyOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new AddMoneyOperator($money);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWithSameCurrencies(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new AddMoneyOperator($money);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('200', $output->getAmount()->toString());
    }

    /**
     * @covers ::apply
     */
    public function testApplyWillThrowExceptionWhenCurrenciesAreDifferent(): void
    {
        $money    = new Money(100, new Currency('usd'));
        $operator = new AddMoneyOperator(Money::JPY(1000));

        $this->expectException(MoneyException::class);
        $operator->apply($money);
    }
}
