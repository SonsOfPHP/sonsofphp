<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\FeatureInterface;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

final class FeatureTest extends TestCase
{
    private $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $feature = new Feature('example', $this->toggle);

        $this->assertInstanceOf(FeatureInterface::class, $feature);
    }

    public function testItReturnsTheCorrectKey(): void
    {
        $feature = new Feature('example', $this->toggle);

        $this->assertSame('example', $feature->getKey());
    }

    public function testIsEnabled(): void
    {
        $this->toggle->expects($this->once())->method('isEnabled')->willReturn(true);

        $feature = new Feature('example', $this->toggle);

        $this->assertTrue($feature->isEnabled(new Context()));
    }
}
