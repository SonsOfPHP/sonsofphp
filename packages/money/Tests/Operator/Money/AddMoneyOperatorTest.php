<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator
 */
final class AddMoneyOperatorTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $money = new Money(100, new Currency('usd'));
        $operator = new AddMoneyOperator($money);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    public function testApplyWithSameCurrencies(): void
    {
        $money = new Money(100, new Currency('usd'));
        $operator = new AddMoneyOperator($money);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('200', $output->getAmount()->toString());
    }
}
