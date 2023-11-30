<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 *
 * @internal
 */
final class AddAmountOperatorTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount   = new Amount(100);
        $operator = new AddAmountOperator($amount);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    /**
     * @covers ::__construct
     * @covers ::apply
     */
    public function testApplyWillAddAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(100);
        $operator = new AddAmountOperator($amount);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('200', $output->toString());
    }
}
