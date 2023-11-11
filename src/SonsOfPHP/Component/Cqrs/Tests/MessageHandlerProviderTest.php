<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\Exception\NoHandlerFoundExceptionInterface;
use SonsOfPHP\Contract\Cqrs\MessageHandlerProviderInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cqrs\MessageHandlerProvider
 *
 * @uses \SonsOfPHP\Component\Cqrs\MessageHandlerProvider
 */
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

    /**
     * @covers ::add
     */
    public function testAddWithHandlerClass(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new \stdClass(), new class () {
            public function __invoke(): void {}
        });

        $property = new \ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    /**
     * @covers ::add
     */
    public function testAddWithObject(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new \stdClass(), function (): void {});

        $property = new \ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    /**
     * @covers ::add
     */
    public function testAddWithString(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add('stdClass', function (): void {});

        $property = new \ReflectionProperty($provider, 'handlers');
        $handlers = $property->getValue($provider);

        $this->assertArrayHasKey('stdClass', $handlers);
    }

    /**
     * @covers ::getHandlerForMessage
     */
    public function testGetHandlerForMessageWhenClassIsInvokable(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new \stdClass(), new class () {
            public function __invoke(): void {}
        });

        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    /**
     * @covers ::getHandlerForMessage
     */
    public function testGetHandlerForMessageWhenNoHandlerExists(): void
    {
        $provider = new MessageHandlerProvider();
        $this->expectException(NoHandlerFoundExceptionInterface::class);
        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    /**
     * @covers ::getHandlerForMessage
     */
    public function testGetHandlerForMessageWhenStringIsPassedIn(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new \stdClass(), function (): void {});

        $this->assertNotNull($provider->getHandlerForMessage('stdClass'));
    }

    /**
     * @covers ::getHandlerForMessage
     */
    public function testGetHandlerForMessageWhenObjectIsPassedIn(): void
    {
        $provider = new MessageHandlerProvider();
        $provider->add(new \stdClass(), function (): void {});

        $this->assertNotNull($provider->getHandlerForMessage(new \stdClass()));
    }
}
