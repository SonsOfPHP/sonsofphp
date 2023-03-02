<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests\Message\Enricher\Handler;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\HttpRequestMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\Message\Enricher\Handler\HttpRequestMessageEnricherHandler
 *
 * @internal
 */
final class HttpRequestMessageEnricherHandlerTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $handler = new HttpRequestMessageEnricherHandler(new RequestStack());

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }

    /**
     * @covers ::enrich
     */
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

    /**
     * @covers ::enrich
     */
    public function testItWillNotEnrichTheMessageWhenThereIsNoMainRequest(): void
    {
        $stack = new RequestStack();

        $handler = new HttpRequestMessageEnricherHandler($stack);

        $message = $this->createMock(MessageInterface::class);
        $message->expects($this->never())->method('withMetadata');

        $handler->enrich($message);
    }
}
