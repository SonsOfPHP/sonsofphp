<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Toggle\AffirmativeToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[Group('feature-toggle')]
#[CoversClass(AffirmativeToggle::class)]
#[UsesClass(MockToggle::class)]
final class AffirmativeToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AffirmativeToggle([]);

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testIsEnabledWhenAllTogglesAreEnabled(): void
    {
        $toggle = new AffirmativeToggle([
            new MockToggle(enabled: true),
            new MockToggle(enabled: true),
            new MockToggle(enabled: true),
        ]);

        $this->assertTrue($toggle->isEnabled());
    }

    public function testIsEnabledWhenOneToggleIsDisabled(): void
    {
        $toggle = new AffirmativeToggle([
            new MockToggle(enabled: true),
            new MockToggle(enabled: false),
            new MockToggle(enabled: true),
        ]);

        $this->assertFalse($toggle->isEnabled());
    }
}
