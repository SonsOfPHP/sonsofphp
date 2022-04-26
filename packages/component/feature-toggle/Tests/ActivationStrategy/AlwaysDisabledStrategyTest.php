<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\ActivationStrategy;

use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\AlwaysDisabledStrategy;
use SonsOfPHP\Component\FeatureToggle\ActivationStrategy\ActivationStrategyInterface;
use PHPUnit\Framework\TestCase;

final class AlwaysDisabledStrategyTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $strategy = new AlwaysDisabledStrategy();

        $this->assertInstanceOf(ActivationStrategyInterface::class, $strategy);
    }

    public function testEnabled(): void
    {
        $strategy = new AlwaysDisabledStrategy();

        $this->assertFalse($strategy->enabled());
    }
}
