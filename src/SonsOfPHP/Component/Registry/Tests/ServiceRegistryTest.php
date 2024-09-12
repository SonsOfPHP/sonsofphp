<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Registry\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Registry\ServiceRegistry;
use SonsOfPHP\Contract\Registry\ServiceRegistryInterface;
use SonsOfPHP\Contract\Registry\ExistingServiceExceptionInterface;
use SonsOfPHP\Contract\Registry\NonExistingServiceExceptionInterface;

#[CoversClass(ServiceRegistry::class)]
final class ServiceRegistryTest extends TestCase
{
    private ServiceRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new ServiceRegistry('Exception');
    }

    public function testItHasTheCorrectInterface(): void
    {
        $this->assertInstanceOf(ServiceRegistryInterface::class, $this->registry);
    }

    public function testItCanRegisterNewServices(): void
    {
        $identifier = 'exception';
        $this->assertCount(0, $this->registry->all());
        $this->assertFalse($this->registry->has($identifier));
        $this->registry->register($identifier, new \Exception());
        $this->assertCount(1, $this->registry->all());
        $this->assertTrue($this->registry->has($identifier));
    }

    public function testItWillThrowCorrectExceptionWhenServiceIdentifierExists(): void
    {
        $this->registry->register('exception', new \Exception());
        $this->expectException(ExistingServiceExceptionInterface::class);
        $this->registry->register('exception', new \Exception());
    }

    public function testItWillThrowCorrectExceptionWhenServiceIsWrongType(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->registry->register('stdclass', new \stdClass());
    }

    public function testItWillReturnAllServicesInTheCorrectFormat(): void
    {
        $this->registry->register('exception', new \Exception());
        $services = $this->registry->all();
        $this->assertArrayHasKey('exception', $services);
    }

    public function testItCanUnregisterServices(): void
    {
        $identifier = 'exception';
        $this->registry->register($identifier, new \Exception());
        $this->assertTrue($this->registry->has($identifier));
        $this->registry->unregister($identifier);
        $this->assertFalse($this->registry->has($identifier));
    }

    public function testItWillThrowCorrectExceptionWhenUnregisteringNonExistingService(): void
    {
        $this->expectException(NonExistingServiceExceptionInterface::class);
        $this->registry->unregister('stdclass');
    }

    public function testItIsAbleToRetrieveRegisteredService(): void
    {
        $identifier = 'exception';
        $service    = new \Exception();
        $this->registry->register($identifier, $service);
        $this->assertSame($service, $this->registry->get($identifier));
    }

    public function testItWillThrowCorrectExceptionWhenGettingNonExistingService(): void
    {
        $this->expectException(NonExistingServiceExceptionInterface::class);
        $this->registry->get('stdclass');
    }
}
