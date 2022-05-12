<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventSourcing\Bridge\Symfony\EventMessageBus;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

final class EventMessageBusTest extends TestCase
{
    private MessageBusInterface $messageBus;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $eventBus = new EventMessageBus($this->messageBus);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventBus);
    }

    public function testItWillDispatch(): void
    {
        $message = $this->createMock(MessageInterface::class);
        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope($message));

        $eventBus = new EventMessageBus($this->messageBus);
        $eventBus->dispatch($message);
    }
}
