<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysEnabledToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(AlwaysEnabledToggle::class)]
#[UsesClass(Context::class)]
final class AlwaysEnabledToggleTest extends TestCase
{
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
