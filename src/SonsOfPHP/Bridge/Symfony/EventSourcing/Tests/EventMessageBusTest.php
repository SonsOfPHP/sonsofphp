<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Bridge\Symfony\EventSourcing\EventMessageBus;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\EventMessageBus
 *
 * @uses \SonsOfPHP\Bridge\Symfony\EventSourcing\EventMessageBus
 */
final class EventMessageBusTest extends TestCase
{
    private MessageBusInterface $messageBus;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $eventBus = new EventMessageBus($this->messageBus);

        $this->assertInstanceOf(EventDispatcherInterface::class, $eventBus); // @phpstan-ignore-line
    }

    /**
     * @covers ::dispatch
     */
    public function testItWillDispatch(): void
    {
        $message = $this->createMock(MessageInterface::class);
        // @phpstan-ignore-next-line
        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope($message));

        $eventBus = new EventMessageBus($this->messageBus);
        $eventBus->dispatch($message);
    }
}
