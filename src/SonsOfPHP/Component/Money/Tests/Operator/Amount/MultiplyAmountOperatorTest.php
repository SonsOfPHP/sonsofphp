<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class MultiplyAmountOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new MultiplyAmountOperator(10);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWillMultiplyAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(10);
        $operator = new MultiplyAmountOperator(10);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('100', $output->toString());
    }
}
