<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Feature;
use SonsOfPHP\Component\FeatureToggle\Provider\FeatureToggleProviderInterface;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Component\FeatureToggle\ToggleInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider
 *
 * @internal
 */
final class InMemoryFeatureToggleProviderTest extends TestCase
{
    private $toggle;

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
     * @covers ::getFeatures
     */
    public function testAddingFeatures(): void
    {
        $features = [
            new Feature('test.one', $this->toggle),
            new Feature('test.two', $this->toggle),
        ];

        $provider = new InMemoryFeatureToggleProvider($features);

        $this->assertCount(2, iterator_to_array($provider->getFeatures()));
    }
}
