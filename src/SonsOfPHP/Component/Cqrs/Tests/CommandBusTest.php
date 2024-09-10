<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\AbstractBus;
use SonsOfPHP\Component\Cqrs\CommandBus;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Contract\Cqrs\CommandBusInterface;
use stdClass;

#[CoversClass(CommandBus::class)]
#[UsesClass(AbstractBus::class)]
final class CommandBusTest extends TestCase
{
    private MockObject $provider;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(MessageHandlerProvider::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $bus = new CommandBus();

        $this->assertInstanceOf(CommandBusInterface::class, $bus);
    }

    public function testAddHandler(): void
    {
        $this->provider->expects($this->once())->method('add');
        $bus = new CommandBus($this->provider);
        $bus->addHandler(new stdClass(), function (): void {});
    }

    public function testDispatch(): void
    {
        $this->provider->expects($this->once())->method('getHandlerForMessage')->willReturn(function (): void {});
        $bus = new CommandBus($this->provider);
        $bus->dispatch(new stdClass());
    }
}
