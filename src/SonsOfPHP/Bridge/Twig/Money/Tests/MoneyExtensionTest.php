<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Twig\Money\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Twig\Money\MoneyExtension;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyFormatterInterface;
use Twig\Extension\ExtensionInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Twig\Money\MoneyExtension
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Bridge\Twig\Money\MoneyExtension
 */
final class MoneyExtensionTest extends TestCase
{
    private $formatter;

    public function setUp(): void
    {
        $this->formatter = $this->createMock(MoneyFormatterInterface::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $extension = new MoneyExtension($this->formatter);

        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    /**
     * @covers ::getFilters
     */
    public function testGetFilters(): void
    {
        $extension = new MoneyExtension($this->formatter);

        $this->assertGreaterThan(0, $extension->getFilters());
    }

    /**
     * @covers ::formatMoney
     */
    public function testItCanFormatMoney(): void
    {
        $this->formatter->expects($this->once())->method('format')->willReturn('');

        $extension = new MoneyExtension($this->formatter);
        $extension->formatMoney(Money::USD(10));
    }
}
