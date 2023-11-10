<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\CommandBus;
use SonsOfPHP\Contract\Cqrs\CommandBusInterface;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cqrs\CommandBus
 *
 * @uses \SonsOfPHP\Component\Cqrs\CommandBus
 * @uses \SonsOfPHP\Component\Cqrs\AbstractBus
 */
final class CommandBusTest extends TestCase
{
    private $provider;

    public function setUp(): void
    {
        $this->provider = $this->createMock(MessageHandlerProvider::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $bus = new CommandBus();

        $this->assertInstanceOf(CommandBusInterface::class, $bus);
    }

    /**
     * @covers ::addHandler
     */
    public function testAddHandler(): void
    {
        $this->provider->expects($this->once())->method('add');
        $bus = new CommandBus($this->provider);
        $bus->addHandler(new \stdClass(), function (): void {});
    }

    /**
     * @covers ::dispatch
     */
    public function testDispatch(): void
    {
        $this->provider->expects($this->once())->method('getHandlerForMessage')->willReturn(function (): void {});
        $bus = new CommandBus($this->provider);
        $bus->dispatch(new \stdClass());
    }
}
