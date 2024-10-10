<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests\Operator\Amount;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Contract\Money\AmountOperatorInterface;

#[CoversClass(DivideAmountOperator::class)]
#[UsesClass(Amount::class)]
final class DivideAmountOperatorTest extends TestCase
{
    #[CoversNothing]
    public function testItHasTheCorrectInterface(): void
    {
        $operator = new DivideAmountOperator(100);

        $this->assertInstanceOf(AmountOperatorInterface::class, $operator);
    }

    public function testApplyWillDivideAmountAndReturnNewObject(): void
    {
        $amount   = new Amount(100);
        $operator = new DivideAmountOperator(5);

        $output = $operator->apply($amount);

        $this->assertNotSame($amount, $output);
        $this->assertSame('20', $output->toString());
    }
}
