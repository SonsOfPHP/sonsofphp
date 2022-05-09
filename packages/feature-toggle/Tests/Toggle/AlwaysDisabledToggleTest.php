<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Toggle;

use SonsOfPHP\Component\FeatureToggle\Toggle\AlwaysDisabledToggle;
use SonsOfPHP\Component\FeatureToggle\Context;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;
use PHPUnit\Framework\TestCase;

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
