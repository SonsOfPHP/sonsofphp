<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Toggle\AffirmativeToggle;
use SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Toggle\AffirmativeToggle
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\AffirmativeToggle
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 * @uses \SonsOfPHP\Component\FeatureToggle\Toggle\MockToggle
 */
final class AffirmativeToggleTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AffirmativeToggle([]);

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    /**
     * @covers ::isEnabled
     */
    public function testIsEnabledWhenAllTogglesAreEnabled(): void
    {
        $toggle = new AffirmativeToggle([
            new MockToggle(enabled: true),
            new MockToggle(enabled: true),
            new MockToggle(enabled: true),
        ]);

        $this->assertTrue($toggle->isEnabled());
    }

    /**
     * @covers ::isEnabled
     */
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
