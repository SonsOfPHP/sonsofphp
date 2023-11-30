<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Feature
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Context
 */
final class FeatureTest extends TestCase
{
    private $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $feature = new Feature('example', $this->toggle);

        $this->assertInstanceOf(FeatureInterface::class, $feature);
    }

    /**
     * @covers ::__construct
     * @covers ::getKey
     */
    public function testItReturnsTheCorrectKey(): void
    {
        $feature = new Feature('example', $this->toggle);

        $this->assertSame('example', $feature->getKey());
    }

    /**
     * @covers ::__construct
     * @covers ::isEnabled
     */
    public function testIsEnabled(): void
    {
        $this->toggle->expects($this->once())->method('isEnabled')->willReturn(true);

        $feature = new Feature('example', $this->toggle);

        $this->assertTrue($feature->isEnabled(new Context()));
    }
}
