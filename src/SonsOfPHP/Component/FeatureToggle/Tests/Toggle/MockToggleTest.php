<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(MockToggle::class)]
#[UsesClass(Context::class)]
final class MockToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new MockToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testIsEnabledWillReturnTrueByDefault(): void
    {
        $toggle = new MockToggle();

        $this->assertTrue($toggle->isEnabled());
    }

    public function testIsEnabledWillReturnTrueWhenEnabledIsSetToTrue(): void
    {
        $toggle = new MockToggle(enabled: true);

        $this->assertTrue($toggle->isEnabled());
    }

    public function testIsEnabledWillReturnFalseWhenEnabledIsSetToFalse(): void
    {
        $toggle = new MockToggle(enabled: false);

        $this->assertFalse($toggle->isEnabled());
    }
}
