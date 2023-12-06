<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Container\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SonsOfPHP\Component\Container\Container;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Container\Container
 *
 * @uses \SonsOfPHP\Component\Container\Container
 */
final class ContainerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $container = new Container();

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    /**
     * @covers ::has
     */
    public function testhHas(): void
    {
        $container = new Container();

        $this->assertFalse($container->has('service.id'));
        $container->set('service.id', function (): void {});
        $this->assertTrue($container->has('service.id'));
    }

    /**
     * @covers ::get
     */
    public function testGetWhenServiceNotFound(): void
    {
        $container = new Container();

        $this->expectException(NotFoundExceptionInterface::class);
        $container->get('nope');
    }

    /**
     * @covers ::get
     */
    public function testGetAlwaysReturnSameInstance(): void
    {
        $container = new Container();
        $container->set('service.id', fn() => new \stdClass());
        $service = $container->get('service.id');
        $this->assertSame($service, $container->get('service.id'));
    }

    /**
     * @covers ::get
     */
    public function testGetWillCacheService(): void
    {
        $container = new Container();
        $cached    = new \ReflectionProperty($container, 'cachedServices');

        $container->set('service.id', fn() => new \stdClass());
        $service = $container->get('service.id');
        $this->assertCount(1, $cached->getValue($container));
    }

    /**
     * @covers ::set
     */
    public function testSet(): void
    {
        $container = new Container();
        $services = new \ReflectionProperty($container, 'services');
        $this->assertCount(0, $services->getValue($container));

        $container->set('service.id', function (): void {});
        $this->assertCount(1, $services->getValue($container));
    }

    /**
     * @covers ::set
     */
    public function testSetWhenNotCallable(): void
    {
        $container = new Container();

        $this->expectException(ContainerExceptionInterface::class);
        $container->set('service.id', 'this is not callable');
    }
}
