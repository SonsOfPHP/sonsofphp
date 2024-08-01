<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

#[CoversClass(SubtractAmountOperator::class)]
#[UsesClass(Amount::class)]
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

    public function testApplyWillSubtractAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(75);
        $operator = new SubtractAmountOperator($amount);

        $output = $operator->apply(new Amount(100));

        $this->assertNotSame($amount, $output);
        $this->assertSame('25', $output->toString());
    }
}
