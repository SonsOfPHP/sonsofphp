<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\ActivationStrategy;

use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\AlwaysEnabledStrategy;
use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\ActivationStrategyInterface;
use PHPUnit\Framework\TestCase;

final class AlwaysEnabledStrategyTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $strategy = new AlwaysEnabledStrategy();

        $this->assertInstanceOf(ActivationStrategyInterface::class, $strategy);
    }

    public function testEnabled(): void
    {
        $strategy = new AlwaysEnabledStrategy();

        $this->assertTrue($strategy->enabled());
    }
}
