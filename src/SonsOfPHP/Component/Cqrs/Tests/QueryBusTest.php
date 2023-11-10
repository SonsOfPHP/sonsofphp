<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\QueryBus;
use SonsOfPHP\Contract\Cqrs\QueryBusInterface;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cqrs\QueryBus
 *
 * @uses \SonsOfPHP\Component\Cqrs\QueryBus
 * @uses \SonsOfPHP\Component\Cqrs\AbstractBus
 */
final class QueryBusTest extends TestCase
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
        $bus = new QueryBus();

        $this->assertInstanceOf(QueryBusInterface::class, $bus);
    }

    /**
     * @covers ::addHandler
     */
    public function testAddHandler(): void
    {
        $this->provider->expects($this->once())->method('add');
        $bus = new QueryBus($this->provider);
        $bus->addHandler(new \stdClass(), function (): void {});
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $this->provider->expects($this->once())->method('getHandlerForMessage')->willReturn(fn() => 'testing');
        $bus = new QueryBus($this->provider);
        $this->assertSame('testing', $bus->handle(new \stdClass()));
    }
}
