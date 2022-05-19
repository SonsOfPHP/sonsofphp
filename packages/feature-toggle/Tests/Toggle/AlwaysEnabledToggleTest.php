<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle
 */
final class AlwaysEnabledToggleTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AlwaysEnabledToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::isEnabled
     */
    public function testItReturnsTrue(): void
    {
        $toggle = new AlwaysEnabledToggle();

        $this->assertTrue($toggle->isEnabled(new Context()));
    }
}
