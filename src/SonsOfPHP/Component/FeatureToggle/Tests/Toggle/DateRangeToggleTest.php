<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\DateRangeToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(DateRangeToggle::class)]
#[UsesClass(DateRangeToggle::class)]
#[UsesClass(Context::class)]
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
