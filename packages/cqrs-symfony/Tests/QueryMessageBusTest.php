<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\Cqrs\QueryMessageBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\Cqrs\QueryMessageBus
 *
 * @internal
 */
final class QueryMessageBusTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::handle
     */
    public function testItWillUseMessageBusToDispatchQuery(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects($this->once())->method('dispatch')->willReturn(
            new Envelope(new \stdClass(), [new HandledStamp('result', 'handlerName')])
        );
        $queryBus = new QueryMessageBus($bus);

        $queryBus->handle(new \stdClass());
    }
}
