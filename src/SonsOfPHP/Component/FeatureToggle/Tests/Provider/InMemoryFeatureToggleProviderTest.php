<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;

#[CoversClass(InMemoryFeatureToggleProvider::class)]
#[UsesClass(Feature::class)]
final class InMemoryFeatureToggleProviderTest extends TestCase
{
    private MockObject|ToggleInterface $toggle;

    protected function setUp(): void
    {
        $this->toggle = $this->createMock(ToggleInterface::class);
    }

    /**
     * @coversNothing
     */
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

        $this->assertNotNull($provider->getFeatureToggleByKey('test.one'));
        $this->assertNotNull($provider->getFeatureToggleByKey('test.two'));
        $this->assertNull($provider->getFeatureToggleByKey('test.three'));
    }
}
