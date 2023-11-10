<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 */
final class DateRangeToggleTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new DateRangeToggle(
            start: new \DateTimeImmutable('-90 days'),
            stop: new \DateTimeImmutable('+90 days'),
        );

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::isEnabled
     */
    public function testIsEnabledWorksAsExpected(): void
    {
        $toggle = new DateRangeToggle(
            start: new \DateTimeImmutable('-90 days'),
            stop: new \DateTimeImmutable('+90 days'),
        );

        $this->assertTrue($toggle->isEnabled());
    }

    /**
     * @covers ::isEnabled
     */
    public function testIsEnabledWorksAsExpectedWhenOutsideDateRange(): void
    {
        $toggle = new DateRangeToggle(
            start: new \DateTimeImmutable('-90 days'),
            stop: new \DateTimeImmutable('-30 days'),
        );

        $this->assertFalse($toggle->isEnabled());
    }
}
