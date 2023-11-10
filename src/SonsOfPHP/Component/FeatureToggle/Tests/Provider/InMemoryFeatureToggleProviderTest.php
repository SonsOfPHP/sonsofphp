<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Contract\FeatureToggle\ToggleInterface;
use PHPUnit\Framework\MockObject;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider
 *
 * @uses \SonsOfPHP\Component\FeatureToggle\Feature
 */
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

    /**
     * @covers ::__construct
     * @covers ::addFeature
     * @covers ::getFeatureToggleByKey
     */
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
