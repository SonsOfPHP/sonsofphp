<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 * @coversNothing
 */
#[CoversClass(DateRangeToggle::class)]
final class DateRangeToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new DateRangeToggle(
            start: new DateTimeImmutable('-90 days'),
            stop: new DateTimeImmutable('+90 days'),
        );

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testIsEnabledWorksAsExpected(): void
    {
        $toggle = new DateRangeToggle(
            start: new DateTimeImmutable('-90 days'),
            stop: new DateTimeImmutable('+90 days'),
        );

        $this->assertTrue($toggle->isEnabled());
    }

    public function testIsEnabledWorksAsExpectedWhenOutsideDateRange(): void
    {
        $toggle = new DateRangeToggle(
            start: new DateTimeImmutable('-90 days'),
            stop: new DateTimeImmutable('-30 days'),
        );

        $this->assertFalse($toggle->isEnabled());
    }
}
