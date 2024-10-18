<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\FeatureToggle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\FeatureToggle\Provider\InMemoryFeatureToggleProvider;
use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureAlreadyExistsExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\Exception\FeatureNotFoundExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\Exception\InvalidArgumentExceptionInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureInterface;
use SonsOfPHP\Contract\FeatureToggle\FeatureToggleProviderInterface;

#[Group('feature-toggle')]
#[CoversClass(InMemoryFeatureToggleProvider::class)]
final class InMemoryFeatureToggleProviderTest extends TestCase
{
    private InMemoryFeatureToggleProvider $provider;

    private FeatureInterface&MockObject $feature;

    protected function setUp(): void
    {
        $this->provider = new InMemoryFeatureToggleProvider();

        $this->feature = $this->createMock(FeatureInterface::class);
        $this->feature->method('getKey')->willReturn('test');
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(FeatureToggleProviderInterface::class, $this->provider);
    }

    public function testItsAddWillAddFeature(): void
    {
        $this->assertFalse($this->provider->has('test'));
        $this->provider->add($this->feature);
        $this->assertTrue($this->provider->has('test'));
    }

    public function testItsAddWillThrowExceptionIfAlreadyAdded(): void
    {
        $this->provider->add($this->feature);
        $this->expectException(FeatureAlreadyExistsExceptionInterface::class);
        $this->provider->add($this->feature);
    }

    public function testItsGetCanReturnCorrectFeature(): void
    {
        $this->provider->add($this->feature);
        $this->assertSame($this->feature, $this->provider->get('test'));
    }

    public function testItsGetWillThrowExceptionIfFeatureNotFound(): void
    {
        $this->expectException(FeatureNotFoundExceptionInterface::class);
        $this->provider->get('test');
    }

    public function testItsHasWillThrowExceptionWhenInvalidKeyIsPassed(): void
    {
        $this->expectException(InvalidArgumentExceptionInterface::class);
        $this->provider->has('invalid-key');
    }

    public function testItsAllWillReturnFeaturesInCorrectFormat(): void
    {
        $this->provider->add($this->feature);

        $features = iterator_to_array($this->provider->all());
        $this->assertArrayHasKey('test', $features);
        $this->assertSame($this->feature, $features['test']);
    }
}
