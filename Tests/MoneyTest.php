<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Money\Tests;

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
}
