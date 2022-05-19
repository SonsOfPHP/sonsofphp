<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\AmountInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Amount
 */
final class AmountTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $amount = new Amount(100);
        $this->assertInstanceOf(AmountInterface::class, $amount);
    }

    /**
     * @covers ::getAmount
     */
    public function testGetAmountReturnsCorrectNumber(): void
    {
        $amount = new Amount(100);
        $this->assertSame('100', $amount->getAmount());
    }

    /**
     * @covers ::__toString
     * @covers ::toString
     */
    public function testToStringMethods(): void
    {
        $amount = new Amount(100);
        $this->assertSame('100', (string) $amount);
        $this->assertSame('100', $amount->toString());
    }

    /**
     * @covers ::add
     * @covers ::with
     */
    public function testAdd(): void
    {
        $amount = new Amount(100);

        $this->assertSame('200', $amount->add(new Amount(100))->getAmount());
    }

    /**
     * @covers ::subtract
     * @covers ::with
     */
    public function testSubtract(): void
    {
        $amount = new Amount(100);
        $this->assertSame('50', $amount->subtract(new Amount(50))->getAmount());
    }

    /**
     * @covers ::multiply
     * @covers ::with
     */
    public function testMultiply(): void
    {
        $amount = new Amount(100);

        $this->assertSame('200', $amount->multiply(2)->getAmount());
    }

    /**
     * @covers ::divide
     * @covers ::with
     */
    public function testDivide(): void
    {
        $amount = new Amount(100);

        $this->assertSame('50', $amount->divide(2)->getAmount());
    }

    /**
     * @covers ::isEqualTo
     * @covers ::query
     */
    public function testEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isEqualTo(new Amount(100)));
    }

    /**
     * @covers ::isEqualTo
     * @covers ::query
     */
    public function testNotEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isEqualTo(new Amount(99)));
    }

    /**
     * @covers ::isGreaterThan
     * @covers ::query
     */
    public function testGreaterThan(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isGreaterThan(new Amount(99)));
    }

    /**
     * @covers ::isGreaterThan
     * @covers ::query
     */
    public function testNotGreaterThan(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isGreaterThan(new Amount(101)));
    }

    /**
     * @covers ::isGreaterThanOrEqualTo
     * @covers ::query
     */
    public function testGreaterThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isGreaterThanOrEqualTo(new Amount(100)));
    }

    /**
     * @covers ::isGreaterThanOrEqualTo
     * @covers ::query
     */
    public function testNotGreaterThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isGreaterThanOrEqualTo(new Amount(200)));
    }

    /**
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testLessThan(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isLessThan(new Amount(200)));
    }

    /**
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testNotLessThan(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isLessThan(new Amount(50)));
    }

    /**
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testLessThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isLessThanOrEqualTo(new Amount(100)));
    }

    /**
     * @covers ::isLessThanOrEqualTo
     * @covers ::query
     */
    public function testNotLessThanOrEquals(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isLessThanOrEqualTo(new Amount(50)));
    }

    /**
     * @covers ::isNegative
     * @covers ::query
     */
    public function testIsNegative(): void
    {
        $amount = new Amount(-1);
        $this->assertTrue($amount->isNegative());
    }

    /**
     * @covers ::isNegative
     * @covers ::query
     */
    public function testNotIsNegative(): void
    {
        $amount = new Amount(1);
        $this->assertFalse($amount->isNegative());
    }

    /**
     * @covers ::isPositive
     * @covers ::query
     */
    public function testIsPositive(): void
    {
        $amount = new Amount(100);
        $this->assertTrue($amount->isPositive());
    }

    /**
     * @covers ::isPositive
     * @covers ::query
     */
    public function testNotIsPositive(): void
    {
        $amount = new Amount(-100);
        $this->assertFalse($amount->isPositive());
    }

    /**
     * @covers ::isZero
     * @covers ::query
     */
    public function testIsZero(): void
    {
        $amount = new Amount(0);
        $this->assertTrue($amount->isZero());
    }

    /**
     * @covers ::isZero
     * @covers ::query
     */
    public function testNotIsZero(): void
    {
        $amount = new Amount(100);
        $this->assertFalse($amount->isZero());
    }

    /**
     * @covers ::toInt
     */
    public function testToInt(): void
    {
        $amount = new Amount(100);
        $this->assertSame(100, $amount->toInt());
    }

    /**
     * @covers ::toFloat
     */
    public function testToFloat(): void
    {
        $amount = new Amount(100);
        $this->assertSame(100.0, $amount->toFloat());
    }
}
