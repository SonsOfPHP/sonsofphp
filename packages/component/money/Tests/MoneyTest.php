<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use SonsOfPHP\Component\Money\Exception\MoneyException;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\MoneyInterface;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $money = new Money(100, new Currency('usd'));
        $this->assertInstanceOf(MoneyInterface::class, $money);

        $money = Money::USD(100);
        $this->assertInstanceOf(MoneyInterface::class, $money);
    }

    public function testMoneyFactoryMagicMethod(): void
    {
        $money = Money::USD(100);

        $this->assertSame(100, $money->getAmount());
        $this->assertSame('USD', $money->getCurrency()->getCurrencyCode());
    }

    public function testToStringMagicMethod(): void
    {
        $money = Money::USD(100);
        $this->assertSame('100', (string) $money);
    }

    public function testEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(200);

        $this->assertTrue($money1->equals($money1));
        $this->assertTrue($money1->equals($money2));
        $this->assertFalse($money1->equals($money3));

        $this->assertTrue($money2->equals($money1));
        $this->assertTrue($money2->equals($money2));
        $this->assertFalse($money2->equals($money3));

        $this->assertFalse($money3->equals($money1));
        $this->assertFalse($money3->equals($money2));
        $this->assertTrue($money3->equals($money3));
    }

    public function testCompare(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertSame(-1, $money1->compare($money2));
        $this->assertSame(1, $money2->compare($money1));
        $this->assertSame(0, $money3->compare($money2));
    }

    public function testGreaterThan(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $this->assertTrue($money2->greaterThan($money1));
        $this->assertFalse($money1->greaterThan($money2));
        $this->assertFalse($money1->greaterThan($money1));
    }

    public function testGreaterThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->greaterThan($money2);
    }

    public function testGreaterThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertFalse($money1->greaterThanOrEquals($money2));
        $this->assertTrue($money2->greaterThanOrEquals($money1));
        $this->assertTrue($money3->greaterThanOrEquals($money2));
    }

    public function testGreaterThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->greaterThanOrEquals($money2);
    }

    public function testLessThan(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $this->assertFalse($money2->lessThan($money1));
        $this->assertTrue($money1->lessThan($money2));
        $this->assertFalse($money1->lessThan($money1));
    }

    public function testLessThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->lessThan($money2);
    }

    public function testLessThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertTrue($money1->lessThanOrEquals($money2));
        $this->assertFalse($money2->lessThanOrEquals($money1));
        $this->assertTrue($money3->lessThanOrEquals($money2));
    }

    public function testLessThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->lessThanOrEquals($money2);
    }

    public function testIsNegative(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertTrue($money1->isNegative());
        $this->assertFalse($money2->isNegative());
        $this->assertFalse($money3->isNegative());
    }

    public function testIsPositive(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertFalse($money1->isPositive());
        $this->assertTrue($money2->isPositive());
        $this->assertFalse($money3->isPositive());
    }

    public function testIsZero(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertFalse($money1->isZero());
        $this->assertFalse($money2->isZero());
        $this->assertTrue($money3->isZero());
    }

    public function testAdd(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);

        $output = $money1->add($money2);
        $this->assertSame(200, $output->getAmount());
    }

    public function testAddWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->add($money2);
    }

    public function testSubtract(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);

        $output = $money1->subtract($money2);
        $this->assertSame(0, $output->getAmount());
    }

    public function testSubtractWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyException::class);
        $money1->subtract($money2);
    }

    public function testSubtractWithLargerAmount(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $output = $money1->subtract($money2);
        $this->assertSame(-100, $output->getAmount());
    }

    public function testMultiply(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->multiply(2);
        $this->assertSame(200, $output->getAmount());
    }

    public function testDivide(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->divide(5);
        $this->assertSame(20, $output->getAmount());
    }
}
