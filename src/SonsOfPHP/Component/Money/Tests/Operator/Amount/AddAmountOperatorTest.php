<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

#[CoversClass(AddAmountOperator::class)]
#[UsesClass(Amount::class)]
final class AddAmountOperatorTest extends TestCase
{
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
