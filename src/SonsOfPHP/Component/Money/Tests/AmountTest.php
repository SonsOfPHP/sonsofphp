<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Contract\Money\AmountInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator
 * @coversNothing
 */
#[CoversClass(Amount::class)]
final class AmountTest extends TestCase
{
    public static function validAmountProvider(): iterable
    {
        yield [420];
        yield ['420'];
        yield [-420];
        yield ['-420'];
    }

    public static function invalidAmountProvider(): iterable
    {
        yield [4.20];
        yield ['4.20'];
        yield [-4.20];
        yield ['-4.20'];
    }

    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $this->assertInstanceOf(AmountInterface::class, $amount);
    }

    public function testGetAmountReturnsCorrectNumber(): void
    {
        $amount = new Amount(100);
        $this->assertSame('100', $amount->getAmount());
    }

    public function testToStringMethods(): void
    {
        $amount = new Amount(100);
        $this->assertSame('100', (string) $amount);
        $this->assertSame('100', $amount->toString());
    }

    public function testAdd(): void
    {
        $amount = new Amount(100);

        $this->assertSame('200', $amount->add(new Amount(100))->getAmount());
    }

    public function testSubtract(): void
    {
        $amount = new Amount(100);
        $this->assertSame('50', $amount->subtract(new Amount(50))->getAmount());
    }

    public function testMultiply(): void
    {
        $amount = new Amount(100);

        $this->assertSame('200', $amount->multiply(2)->getAmount());
    }

    public function testDivide(): void
    {
        $amount = new Amount(100);

        $this->assertSame('50', $amount->divide(2)->getAmount());
    }

    public function testEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isEqualTo(new Amount(100)));
    }

    public function testNotEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isEqualTo(new Amount(99)));
    }

    public function testGreaterThan(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isGreaterThan(new Amount(99)));
    }

    public function testNotGreaterThan(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isGreaterThan(new Amount(101)));
    }

    public function testGreaterThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isGreaterThanOrEqualTo(new Amount(100)));
    }

    public function testNotGreaterThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isGreaterThanOrEqualTo(new Amount(200)));
    }

    public function testLessThan(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isLessThan(new Amount(200)));
    }

    public function testNotLessThan(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isLessThan(new Amount(50)));
    }

    public function testLessThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isLessThanOrEqualTo(new Amount(100)));
    }

    public function testNotLessThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isLessThanOrEqualTo(new Amount(50)));
    }

    public function testIsNegative(): void
    {
        $amount = new Amount(-1);
        $this->assertTrue($amount->isNegative());
    }

    public function testNotIsNegative(): void
    {
        $amount = new Amount(1);
        $this->assertFalse($amount->isNegative());
    }

    public function testIsPositive(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isPositive());
    }

    public function testNotIsPositive(): void
    {
        $amount = new Amount(-100);
        $this->assertFalse($amount->isPositive());
    }

    public function testIsZero(): void
    {
        $amount = new Amount(0);
        $this->assertTrue($amount->isZero());
    }

    public function testNotIsZero(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isZero());
    }

    public function testToInt(): void
    {
        $amount = new Amount(100);
        $this->assertSame(100, $amount->toInt());
    }

    public function testToFloat(): void
    {
        $amount = new Amount(100);
        $this->assertSame(100.0, $amount->toFloat());
    }
}
