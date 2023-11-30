<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Money\Money
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator
 * @uses \SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery
 * @uses \SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsNegativeMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsPositiveMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsGreaterThanOrEqualToMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery
 * @uses \SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery
 * @uses \SonsOfPHP\Component\Money\Query\Money\IsEqualToMoneyQuery
 */
final class MoneyTest extends TestCase
{
    public static function validMoneyConstructorArgumentsProvider(): iterable
    {
        yield [420, 'usd'];
        yield [420, new Currency('usd')];
        yield [-420, 'usd'];
        yield [-420, new Currency('usd')];
    }

    /**
     * @covers ::__callStatic
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $money = new Money(100, new Currency('usd'));
        $this->assertInstanceOf(MoneyInterface::class, $money);

        $money = Money::USD(100);
        $this->assertInstanceOf(MoneyInterface::class, $money);
    }

    /**
     * @covers ::__callStatic
     * @covers ::__construct
     * @covers ::getAmount
     * @covers ::getCurrency
     */
    public function testMoneyFactoryMagicMethod(): void
    {
        $money = Money::USD(100);

        $this->assertSame('100', (string) $money->getAmount());
        $this->assertSame('USD', $money->getCurrency()->getCurrencyCode());
    }

    /**
     * @covers ::__toString
     */
    public function testToStringMagicMethod(): void
    {
        $money = Money::USD(100);
        $this->assertSame('100', (string) $money);
    }

    /**
     * @covers ::isEqualTo
     * @covers ::query
     */
    public function testEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(200);

        $this->assertTrue($money1->isEqualTo($money1));
        $this->assertTrue($money1->isEqualTo($money2));
        $this->assertFalse($money1->isEqualTo($money3));

        $this->assertTrue($money2->isEqualTo($money1));
        $this->assertTrue($money2->isEqualTo($money2));
        $this->assertFalse($money2->isEqualTo($money3));

        $this->assertFalse($money3->isEqualTo($money1));
        $this->assertFalse($money3->isEqualTo($money2));
        $this->assertTrue($money3->isEqualTo($money3));
    }

    /**
     * @covers ::compare
     * @covers ::isGreaterThan
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testCompare(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertSame(-1, $money1->compare($money2));
        $this->assertSame(1, $money2->compare($money1));
        $this->assertSame(0, $money3->compare($money2));
    }

    /**
     * @covers ::isGreaterThan
     * @covers ::query
     */
    public function testGreaterThan(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $this->assertTrue($money2->isGreaterThan($money1));
        $this->assertFalse($money1->isGreaterThan($money2));
        $this->assertFalse($money1->isGreaterThan($money1));
    }

    /**
     * @covers ::isGreaterThan
     * @covers ::query
     */
    public function testGreaterThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isGreaterThan($money2);
    }

    /**
     * @covers ::isGreaterThanOrEqualTo
     * @covers ::query
     */
    public function testGreaterThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertFalse($money1->isGreaterThanOrEqualTo($money2));
        $this->assertTrue($money2->isGreaterThanOrEqualTo($money1));
        $this->assertTrue($money3->isGreaterThanOrEqualTo($money2));
    }

    /**
     * @covers ::isGreaterThanOrEqualTo
     * @covers ::query
     */
    public function testGreaterThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isGreaterThanOrEqualTo($money2);
    }

    /**
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testLessThan(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $this->assertFalse($money2->isLessThan($money1));
        $this->assertTrue($money1->isLessThan($money2));
        $this->assertFalse($money1->isLessThan($money1));
    }

    /**
     * @covers ::isLessThan
     * @covers ::query
     */
    public function testLessThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isLessThan($money2);
    }

    /**
     * @covers ::isLessThanOrEqualTo
     * @covers ::query
     */
    public function testLessThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertTrue($money1->isLessThanOrEqualTo($money2));
        $this->assertFalse($money2->isLessThanOrEqualTo($money1));
        $this->assertTrue($money3->isLessThanOrEqualTo($money2));
    }

    /**
     * @covers ::isLessThanOrEqualTo
     * @covers ::query
     */
    public function testLessThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isLessThanOrEqualTo($money2);
    }

    /**
     * @covers ::isNegative
     * @covers ::query
     */
    public function testIsNegative(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertTrue($money1->isNegative());
        $this->assertFalse($money2->isNegative());
        $this->assertFalse($money3->isNegative());
    }

    /**
     * @covers ::isPositive
     * @covers ::query
     */
    public function testIsPositive(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertFalse($money1->isPositive());
        $this->assertTrue($money2->isPositive());
        $this->assertFalse($money3->isPositive());
    }

    /**
     * @covers ::isZero
     * @covers ::query
     */
    public function testIsZero(): void
    {
        $money1 = Money::USD(-100);
        $money2 = Money::USD(100);
        $money3 = Money::USD(0);

        $this->assertFalse($money1->isZero());
        $this->assertFalse($money2->isZero());
        $this->assertTrue($money3->isZero());
    }

    /**
     * @covers ::add
     * @covers ::with
     */
    public function testAdd(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);

        $output = $money1->add($money2);
        $this->assertSame('200', (string) $output->getAmount());
    }

    /**
     * @covers ::add
     * @covers ::with
     */
    public function testAddWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->add($money2);
    }

    /**
     * @covers ::subtract
     * @covers ::with
     */
    public function testSubtract(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);

        $output = $money1->subtract($money2);
        $this->assertSame('0', (string) $output->getAmount());
    }

    /**
     * @covers ::subtract
     * @covers ::with
     */
    public function testSubtractWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->subtract($money2);
    }

    /**
     * @covers ::subtract
     * @covers ::with
     */
    public function testSubtractWithLargerAmount(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $output = $money1->subtract($money2);
        $this->assertSame('-100', (string) $output->getAmount());
    }

    /**
     * @covers ::multiply
     * @covers ::with
     */
    public function testMultiply(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->multiply(2);
        $this->assertSame('200', (string) $output->getAmount());
    }

    /**
     * @covers ::divide
     * @covers ::with
     */
    public function testDivide(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->divide(5);
        $this->assertSame('20', (string) $output->getAmount());
    }

    /**
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $money = Money::USD(420);

        $this->assertSame('{"amount":420,"currency":"USD"}', json_encode($money));
    }
}
