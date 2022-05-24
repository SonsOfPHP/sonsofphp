<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Money;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\MoneyOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 */
final class DivideMoneyOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new DivideMoneyOperator(5);

        $this->assertInstanceOf(MoneyOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWithSameCurrencies(): void
    {
        $money = new Money(100, new Currency('usd'));
        $operator = new DivideMoneyOperator(5);

        $output = $operator->apply($money);

        $this->assertNotSame($money, $output);
        $this->assertSame('20', $output->getAmount()->toString());
    }
}
