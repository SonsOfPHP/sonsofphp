<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Twig\Money\Tests;

use PHPUnit\Framework\TestCase;
use Twig\Extension\ExtensionInterface;
use SonsOfPHP\Bridge\Twig\Money\MoneyExtension;
use SonsOfPHP\Component\Money\Money;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Twig\Money\MoneyExtension
 *
 * @uses \SonsOfPHP\Bridge\Twig\Money\Tests\MoneyExtensionTest
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 */
final class MoneyExtensionTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheRightInterface(): void
    {
        $extension = new MoneyExtension();

        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    /**
     * @covers ::formatMoney
     */
    public function testItCanFormatMoney(): void
    {
        $extension = new MoneyExtension();

        $this->assertSame('$10.00', $extension->formatMoney(Money::USD(10)));
    }
}
