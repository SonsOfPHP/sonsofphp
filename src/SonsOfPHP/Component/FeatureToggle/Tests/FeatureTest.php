<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(Feature::class)]
#[UsesClass(Context::class)]
final class FeatureTest extends TestCase
{
    private MockObject $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);
    }

    #[CoversNothing]
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
