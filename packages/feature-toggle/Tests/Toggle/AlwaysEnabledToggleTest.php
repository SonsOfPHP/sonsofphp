<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;
use PHPUnit\Framework\TestCase;

final class AlwaysEnabledToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AlwaysEnabledToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testItReturnsTrue(): void
    {
        $toggle = new AlwaysEnabledToggle();

        $this->assertTrue($toggle->isEnabled(new Context()));
    }
}
