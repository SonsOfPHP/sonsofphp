<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 */
final class MultiplyMoneyOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new MultiplyMoneyOperator(20);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWithSameCurrencies(): void
    {
        $money = new Money(5, new Currency('usd'));
        $operator = new MultiplyMoneyOperator(20);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('100', $output->getAmount()->toString());
    }
}
