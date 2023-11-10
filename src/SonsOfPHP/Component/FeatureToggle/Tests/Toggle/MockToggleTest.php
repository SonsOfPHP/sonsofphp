<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 */
final class MockToggleTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new MockToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::__construct
     * @covers ::isEnabled
     */
    public function testIsEnabledWillReturnTrueByDefault(): void
    {
        $toggle = new MockToggle();

        $this->assertTrue($toggle->isEnabled());
    }

    /**
     * @covers ::__construct
     * @covers ::isEnabled
     */
    public function testIsEnabledWillReturnTrueWhenEnabledIsSetToTrue(): void
    {
        $toggle = new MockToggle(enabled: true);

        $this->assertTrue($toggle->isEnabled());
    }

    /**
     * @covers ::__construct
     * @covers ::isEnabled
     */
    public function testIsEnabledWillReturnFalseWhenEnabledIsSetToFalse(): void
    {
        $toggle = new MockToggle(enabled: false);

        $this->assertFalse($toggle->isEnabled());
    }
}
