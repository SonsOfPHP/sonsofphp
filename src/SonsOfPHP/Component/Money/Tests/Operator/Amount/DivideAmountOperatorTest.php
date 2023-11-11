<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Contract\Money\Operator\Amount\AmountOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class DivideAmountOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new DivideAmountOperator(100);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWillDivideAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(100);
        $operator = new DivideAmountOperator(5);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('20', $output->toString());
    }
}
