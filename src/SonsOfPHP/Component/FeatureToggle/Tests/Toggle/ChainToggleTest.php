<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(ChainToggle::class)]
#[UsesClass(MockToggle::class)]
final class ChainToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new ChainToggle([]);

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testIsEnabledWhenAtLeastOneIsEnabled(): void
    {
        $toggle = new ChainToggle([
            new MockToggle(enabled: false),
            new MockToggle(enabled: false),
            new MockToggle(enabled: true),
        ]);

        $this->assertTrue($toggle->isEnabled());
    }

    public function testIsEnabledWhenAllTogglesAreDisabled(): void
    {
        $toggle = new ChainToggle([
            new MockToggle(enabled: false),
            new MockToggle(enabled: false),
            new MockToggle(enabled: false),
        ]);

        $this->assertFalse($toggle->isEnabled());
    }
}
