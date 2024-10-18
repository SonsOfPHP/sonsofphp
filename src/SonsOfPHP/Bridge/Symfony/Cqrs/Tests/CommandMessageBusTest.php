<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\Cqrs\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\Cqrs\CommandMessageBus;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

#[CoversClass(CommandMessageBus::class)]
final class CommandMessageBusTest extends TestCase
{
    public function testWithStampsIsImmutable(): void
    {
        $commandBus = new CommandMessageBus($this->createMock(MessageBusInterface::class));

        $anotherCommandBus = $commandBus->withStamps([
            $this->createMock(StampInterface::class),
        ]);

        $this->assertNotSame($commandBus, $anotherCommandBus);
    }

    public function testItWillUseMessageBusToDispatchCommand(): void
    {
        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects($this->once())->method('dispatch')->willReturn(new Envelope($bus));

        $commandBus = new CommandMessageBus($bus);

        $commandBus->dispatch(new stdClass());
    }
}
