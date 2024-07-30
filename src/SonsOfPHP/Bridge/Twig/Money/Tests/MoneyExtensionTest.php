<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Twig\Money\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Twig\Money\MoneyExtension;
use SonsOfPHP\Component\Money\Money;
use SonsOfPHP\Contract\Money\MoneyFormatterInterface;
use Twig\Extension\ExtensionInterface;

/**
 *
 * @uses \SonsOfPHP\Component\Money\Amount
 * @uses \SonsOfPHP\Component\Money\Currency
 * @uses \SonsOfPHP\Component\Money\Money
 * @uses \SonsOfPHP\Bridge\Twig\Money\MoneyExtension
 * @coversNothing
 */
#[CoversClass(MoneyExtension::class)]
final class MoneyExtensionTest extends TestCase
{
    private MockObject $formatter;

    public function setUp(): void
    {
        $this->formatter = $this->createMock(MoneyFormatterInterface::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $extension = new MoneyExtension($this->formatter);

        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }

    public function testGetFilters(): void
    {
        $extension = new MoneyExtension($this->formatter);

        $this->assertGreaterThan(0, $extension->getFilters());
    }

    public function testItCanFormatMoney(): void
    {
        $this->formatter->expects($this->once())->method('format')->willReturn('');

        $extension = new MoneyExtension($this->formatter);
        $extension->formatMoney(Money::USD(10));
    }
}
