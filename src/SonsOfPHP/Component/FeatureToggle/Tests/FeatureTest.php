<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Contract\FeatureToggle\Exception\InvalidArgumentExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[Group('feature-toggle')]
#[CoversClass(Feature::class)]
#[UsesClass(Context::class)]
final class FeatureTest extends TestCase
{
    private Feature $feature;

    private ToggleInterface&MockObject $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);

        $this->feature = new Feature('key', $this->toggle);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(FeatureInterface::class, $this->feature);
    }

    public function testItsKeyIsInmutable(): void
    {
        $this->assertSame('key', $this->feature->getKey());
    }

    public function testItsIsEnabledReturnsTrue(): void
    {
        $this->toggle->expects($this->once())->method('isEnabled')->willReturn(true);

        $this->assertTrue($this->feature->isEnabled(new Context()));
    }

    public function testItWillThrowExceptionForInvalidKey(): void
    {
        $this->expectException(InvalidArgumentExceptionInterface::class);
        new Feature('test-key', $this->toggle);
    }
}
