<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\AmountOperatorInterface;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class SubtractAmountOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount   = new Amount(100);
        $operator = new SubtractAmountOperator($amount);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWillSubtractAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(75);
        $operator = new SubtractAmountOperator($amount);

        $output = $operator->apply(new Amount(100));

        $this->assertNotSame($amount, $output);
        $this->assertSame('25', $output->toString());
    }
}
