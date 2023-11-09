<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cqrs\Tests\Query;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cqrs\Query\QueryBus;
use SonsOfPHP\Contract\Cqrs\Query\QueryBusInterface;
use SonsOfPHP\Component\Cqrs\MessageHandlerProvider;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cqrs\Query\QueryBus
 *
 * @uses \SonsOfPHP\Component\Cqrs\Query\QueryBus
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
        $bus->addHandler(new \stdClass(), function () {});
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $this->provider->expects($this->once())->method('getHandlerForMessage')->willReturn(function () {
            return 'testing';
        });
        $bus = new QueryBus($this->provider);
        $this->assertSame('testing', $bus->handle(new \stdClass()));
    }
}
