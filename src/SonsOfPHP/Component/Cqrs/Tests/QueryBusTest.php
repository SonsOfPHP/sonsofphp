<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\AbstractBus;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;
use SonsOfPHP\Component\Cqrs\QueryBus;
use SonsOfPHP\Contract\Cqrs\QueryBusInterface;
use stdClass;

#[CoversClass(QueryBus::class)]
#[UsesClass(AbstractBus::class)]
final class QueryBusTest extends TestCase
{
    private MockObject $provider;

    public function setUp(): void
    {
        $this->provider = $this->createMock(MessageHandlerProvider::class);
    }

    public function testItHasTheCorrectInterface(): void
    {
        $bus = new QueryBus();

        $this->assertInstanceOf(QueryBusInterface::class, $bus);
    }

    public function testAddHandler(): void
    {
        $this->provider->expects($this->once())->method('add');
        $bus = new QueryBus($this->provider);
        $bus->addHandler(new stdClass(), function (): void {});
    }

    public function testHandle(): void
    {
        $this->provider->expects($this->once())->method('getHandlerForMessage')->willReturn(fn(): string => 'testing');
        $bus = new QueryBus($this->provider);
        $this->assertSame('testing', $bus->handle(new stdClass()));
    }
}
