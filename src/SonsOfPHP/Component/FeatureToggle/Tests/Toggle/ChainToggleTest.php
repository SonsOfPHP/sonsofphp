<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\ChainToggle
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle
 */
final class ChainToggleTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new ChainToggle([]);

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::isEnabled
     */
    public function testIsEnabledWhenAtLeastOneIsEnabled(): void
    {
        $toggle = new ChainToggle([
            new MockToggle(enabled: false),
            new MockToggle(enabled: false),
            new MockToggle(enabled: true),
        ]);

        $this->assertTrue($toggle->isEnabled());
    }

    /**
     * @covers ::isEnabled
     */
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
