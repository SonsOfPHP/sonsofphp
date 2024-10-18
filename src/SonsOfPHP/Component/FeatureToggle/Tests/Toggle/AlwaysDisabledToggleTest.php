<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[Group('feature-toggle')]
#[CoversClass(AlwaysDisabledToggle::class)]
#[UsesClass(Context::class)]
final class AlwaysDisabledToggleTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $toggle = new AlwaysDisabledToggle();

        $this->assertInstanceOf(ToggleInterface::class, $toggle);
    }

    public function testItReturnsFalse(): void
    {
        $toggle = new AlwaysDisabledToggle();

        $this->assertFalse($toggle->isEnabled(new Context()));
    }
}
