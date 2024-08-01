<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Money\Amount;
use SonsOfPHP\Component\Money\Currency;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Component\Money\Operator\Amount\AddAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\DivideAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\MultiplyAmountOperator;
use SonsOfPHP\Component\Money\Operator\Amount\SubtractAmountOperator;
use SonsOfPHP\Component\Money\Operator\Money\AddMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\DivideMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\MultiplyMoneyOperator;
use SonsOfPHP\Component\Money\Operator\Money\SubtractMoneyOperator;
use SonsOfPHP\Component\Money\Query\Amount\IsEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsGreaterThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsLessThanOrEqualToAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsNegativeAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsPositiveAmountQuery;
use SonsOfPHP\Component\Money\Query\Amount\IsZeroAmountQuery;
use SonsOfPHP\Component\Money\Query\Currency\IsEqualToCurrencyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsGreaterThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsLessThanOrEqualToMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsNegativeMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsPositiveMoneyQuery;
use SonsOfPHP\Component\Money\Query\Money\IsZeroMoneyQuery;
use SonsOfPHP\Contract\Money\Exception\MoneyExceptionInterface;
use SonsOfPHP\Contract\Money\MoneyInterface;

#[CoversClass(Money::class)]
#[UsesClass(Amount::class)]
#[UsesClass(Currency::class)]
#[UsesClass(AddMoneyOperator::class)]
#[UsesClass(IsEqualToCurrencyQuery::class)]
#[UsesClass(IsGreaterThanOrEqualToAmountQuery::class)]
#[UsesClass(IsGreaterThanOrEqualToMoneyQuery::class)]
#[UsesClass(SubtractAmountOperator::class)]
#[UsesClass(SubtractMoneyOperator::class)]
#[UsesClass(IsNegativeAmountQuery::class)]
#[UsesClass(IsNegativeMoneyQuery::class)]
#[UsesClass(IsLessThanAmountQuery::class)]
#[UsesClass(IsLessThanMoneyQuery::class)]
#[UsesClass(IsEqualToAmountQuery::class)]
#[UsesClass(IsEqualToMoneyQuery::class)]
#[UsesClass(IsZeroAmountQuery::class)]
#[UsesClass(IsZeroMoneyQuery::class)]
#[UsesClass(DivideAmountOperator::class)]
#[UsesClass(DivideMoneyOperator::class)]
#[UsesClass(IsGreaterThanMoneyQuery::class)]
#[UsesClass(MultiplyAmountOperator::class)]
#[UsesClass(MultiplyMoneyOperator::class)]
#[UsesClass(IsGreaterThanAmountQuery::class)]
#[UsesClass(IsLessThanOrEqualToMoneyQuery::class)]
#[UsesClass(AddAmountOperator::class)]
#[UsesClass(IsLessThanOrEqualToAmountQuery::class)]
#[UsesClass(IsPositiveAmountQuery::class)]
#[UsesClass(IsPositiveMoneyQuery::class)]
final class MoneyTest extends TestCase
{
    public static function validMoneyConstructorArgumentsProvider(): iterable
    {
        yield [420, 'usd'];
        yield [420, new Currency('usd')];
        yield [-420, 'usd'];
        yield [-420, new Currency('usd')];
    }

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

        $this->assertSame('100', (string) $money->getAmount());
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

        $this->assertTrue($money2->isGreaterThan($money1));
        $this->assertFalse($money1->isGreaterThan($money2));
        $this->assertFalse($money1->isGreaterThan($money1));
    }

    public function testGreaterThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isGreaterThan($money2);
    }

    public function testGreaterThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertFalse($money1->isGreaterThanOrEqualTo($money2));
        $this->assertTrue($money2->isGreaterThanOrEqualTo($money1));
        $this->assertTrue($money3->isGreaterThanOrEqualTo($money2));
    }

    public function testGreaterThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isGreaterThanOrEqualTo($money2);
    }

    public function testLessThan(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $this->assertFalse($money2->isLessThan($money1));
        $this->assertTrue($money1->isLessThan($money2));
        $this->assertFalse($money1->isLessThan($money1));
    }

    public function testLessThanWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isLessThan($money2);
    }

    public function testLessThanOrEquals(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);
        $money3 = Money::USD(200);

        $this->assertTrue($money1->isLessThanOrEqualTo($money2));
        $this->assertFalse($money2->isLessThanOrEqualTo($money1));
        $this->assertTrue($money3->isLessThanOrEqualTo($money2));
    }

    public function testLessThanOrEqualsWithDifferentCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->isLessThanOrEqualTo($money2);
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
        $this->assertSame('200', (string) $output->getAmount());
    }

    public function testAddWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->add($money2);
    }

    public function testSubtract(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(100);

        $output = $money1->subtract($money2);
        $this->assertSame('0', (string) $output->getAmount());
    }

    public function testSubtractWithDifferenctCurrencies(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::EUR(100);

        $this->expectException(MoneyExceptionInterface::class);
        $money1->subtract($money2);
    }

    public function testSubtractWithLargerAmount(): void
    {
        $money1 = Money::USD(100);
        $money2 = Money::USD(200);

        $output = $money1->subtract($money2);
        $this->assertSame('-100', (string) $output->getAmount());
    }

    public function testMultiply(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->multiply(2);
        $this->assertSame('200', (string) $output->getAmount());
    }

    public function testDivide(): void
    {
        $money1 = Money::USD(100);

        $output = $money1->divide(5);
        $this->assertSame('20', (string) $output->getAmount());
    }

    public function testJsonSerialize(): void
    {
        $money = Money::USD(420);

        $this->assertSame('{"amount":420,"currency":"USD"}', json_encode($money));
    }
}
