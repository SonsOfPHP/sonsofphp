<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 * @coversNothing
 */
#[CoversClass(AlwaysEnabledToggle::class)]
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

    public function testItReturnsTrue(): void
    {
        $toggle = new AlwaysEnabledToggle();

        $this->assertTrue($toggle->isEnabled(new Context()));
    }
}
