<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @internal
 * @coversNothing
 */
#[CoversClass(AddAmountOperator::class)]
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

    public function testApplyWillAddAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(100);
        $operator = new AddAmountOperator($amount);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('200', $output->toString());
    }
}
