<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message\Enricher\Handler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\HttpRequestMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[CoversClass(HttpRequestMessageEnricherHandler::class)]
#[UsesClass(HttpRequestMessageEnricherHandler::class)]
#[CoversNothing]
final class HttpRequestMessageEnricherHandlerTest extends TestCase
{
    public function testItHasTheRightInterface(): void
    {
        $handler = new HttpRequestMessageEnricherHandler(new RequestStack());

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    public function testItWillEnrichTheMessageWithMainRequest(): void
    {
        $request = new Request();

        $stack = new RequestStack();
        $stack->push($request);

        $handler = new HttpRequestMessageEnricherHandler($stack);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->once())->method('withMetadata');

        $handler->enrich($message);
    }

    public function testItWillNotEnrichTheMessageWhenThereIsNoMainRequest(): void
    {
        $stack = new RequestStack();

        $handler = new HttpRequestMessageEnricherHandler($stack);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->never())->method('withMetadata');

        $handler->enrich($message);
    }
}
