<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message\Enricher\Handler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\UuidEventIdMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use SonsOfPHP\Component\EventSourcing\Metadata;
use Symfony\Component\Uid\Uuid;

#[CoversClass(UuidEventIdMessageEnricherHandler::class)]
final class UuidEventIdMessageEnricherHandlerTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $handler = new UuidEventIdMessageEnricherHandler();

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    public function testItWillGenerateUuid(): void
    {
        $handler = new UuidEventIdMessageEnricherHandler();

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())
            ->method('withMetadata')
            ->with($this->callback(fn($metadata): bool => Uuid::isValid($metadata[Metadata::EVENT_ID])))
        ;

        $handler->enrich($message);
    }
}
