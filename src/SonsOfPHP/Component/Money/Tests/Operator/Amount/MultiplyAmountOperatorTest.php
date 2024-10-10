<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

#[CoversClass(MultiplyAmountOperator::class)]
#[UsesClass(Amount::class)]
final class MultiplyAmountOperatorTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new MultiplyAmountOperator(10);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    public function testApplyWillMultiplyAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(10);
        $operator = new MultiplyAmountOperator(10);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('100', $output->toString());
    }
}
