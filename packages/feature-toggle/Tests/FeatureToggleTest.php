<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use SonsOfPHP\Component\FeatureToggle\FeatureToggle;
use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\AlwaysDisabledStrategy;
use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\AlwaysEnabledStrategy;
use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\ActivationStrategyInterface;
use SonsOfPHP\Component\FeatureToggle\FeatureToggleInterface;
use PHPUnit\Framework\TestCase;

final class FeatureToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $mock = $this->createMock(ActivationStrategyInterface::class);
        $toggle = new FeatureToggle($mock);

        $this->assertInstanceOf(FeatureToggleInterface::class, $toggle);
    }

    public function testIsEnabledWithDisabledStrategy(): void
    {
        $strategy = new AlwaysDisabledStrategy();
        $toggle   = new FeatureToggle($strategy);

        $this->assertFalse($toggle->isEnabled());
    }

    public function testIsEnabledWithEnabledStrategy(): void
    {
        $strategy = new AlwaysEnabledStrategy();
        $toggle   = new FeatureToggle($strategy);

        $this->assertTrue($toggle->isEnabled());
    }
}
