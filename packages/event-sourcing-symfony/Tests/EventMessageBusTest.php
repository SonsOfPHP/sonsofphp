<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony\Tests;

use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use SonsOfPHP\Component\EventSourcing\Bridge\Symfony\EventMessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

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
}
