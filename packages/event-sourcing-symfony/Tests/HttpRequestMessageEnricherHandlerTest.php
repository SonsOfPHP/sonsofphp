<?php

declare(strict_types=1);

namespace SonsOfPHP\Bridge\Symfony\EventSourcing\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Bridge\Symfony\EventSourcing\HttpRequestMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @coversDefaultClass \SonsOfPHP\Bridge\Symfony\EventSourcing\HttpRequestMessageEnricherHandler
 */
final class HttpRequestMessageEnricherHandlerTest extends TestCase
{
    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = $this->createStub(RequestStack::class);
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheRightInterface(): void
    {
        $handler = new HttpRequestMessageEnricherHandler($this->requestStack);

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }
}
