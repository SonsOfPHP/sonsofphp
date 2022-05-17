<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\Provider\FeatureToggleProviderInterface;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

final class InMemoryFeatureToggleProviderTest extends TestCase
{
    private $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $provider = new InMemoryFeatureToggleProvider();

        $this->assertInstanceOf(FeatureToggleProviderInterface::class, $provider);
    }

    public function testAddingFeatures(): void
    {
        $features = [
            new Feature('test.one', $this->toggle),
            new Feature('test.two', $this->toggle),
        ];

        $provider = new InMemoryFeatureToggleProvider($features);

        $this->assertCount(2, $provider->getFeatures());
    }
}
