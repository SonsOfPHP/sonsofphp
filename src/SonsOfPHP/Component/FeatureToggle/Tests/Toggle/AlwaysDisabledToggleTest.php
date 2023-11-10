<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 */
final class AlwaysDisabledToggleTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AlwaysDisabledToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::isEnabled
     */
    public function testItReturnsFalse(): void
    {
        $toggle = new AlwaysDisabledToggle();

        $this->assertFalse($toggle->isEnabled(new Context()));
    }
}
