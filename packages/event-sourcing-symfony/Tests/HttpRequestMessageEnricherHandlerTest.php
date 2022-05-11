<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\EventSourcing\Bridge\Symfony\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\EventSourcing\Bridge\Symfony\HttpRequestMessageEnricherHandler;
use SonsOfPHP\Component\EventSourcing\Message\Enricher\Handler\MessageEnricherHandlerInterface;
use SonsOfPHP\Component\EventSourcing\Message\MessageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class HttpRequestMessageEnricherHandlerTest extends TestCase
{
    private RequestStack $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = $this->createStub(RequestStack::class);
    }

    public function testItHasTheRightInterface(): void
    {
        $handler = new HttpRequestMessageEnricherHandler($this->requestStack);

        $this->assertInstanceOf(MessageEnricherHandlerInterface::class, $handler);
    }
}
