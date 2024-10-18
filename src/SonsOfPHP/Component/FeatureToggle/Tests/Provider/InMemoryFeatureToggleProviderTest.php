<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;

#[Group('feature-toggle')]
#[CoversClass(InMemoryFeatureToggleProvider::class)]
#[UsesClass(Feature::class)]
final class InMemoryFeatureToggleProviderTest extends TestCase
{
    private InMemoryFeatureToggleProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new InMemoryFeatureToggleProvider();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(FeatureToggleProviderInterface::class, $this->provider);
    }
}
