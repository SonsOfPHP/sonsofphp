<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\Exception\NoHandlerFoundExceptionInterface;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;
use stdClass;

/**
 * @uses \SonsOfPHP\Component\Cqrs\MessageHandlerProvider
 * @coversNothing
 */
#[CoversClass(MessageHandlerProvider::class)]
final class MessageHandlerProviderTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $provider = new MessageHandlerProvider();

        $this->assertInstanceOf(MessageHandlerProviderInterface::class, $provider);
    }

    public function testAddWithHandlerClass(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new stdClass(), new class {
            public function __invoke(): void {}
        });

        $property = new ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    public function testAddWithObject(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new stdClass(), function (): void {});

        $property = new ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    public function testAddWithString(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add('stdClass', function (): void {});

        $property = new ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    public function testGetHandlerForMessageWhenClassIsInvokable(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new stdClass(), new class {
            public function __invoke(): void {}
        });

        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    public function testGetHandlerForMessageWhenNoHandlerExists(): void
    {
        $provider = new MessageHandlerProvider();
        $this->expectException(NoHandlerFoundExceptionInterface::class);
        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    public function testGetHandlerForMessageWhenStringIsPassedIn(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new stdClass(), function (): void {});

        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    public function testGetHandlerForMessageWhenObjectIsPassedIn(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new stdClass(), function (): void {});

        $this->assertNotNull($provider->getHandlerForMessage(new stdClass()));
    }
}
