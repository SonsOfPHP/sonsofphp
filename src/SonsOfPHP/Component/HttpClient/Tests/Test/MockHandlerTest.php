<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests\Test;

use SonsOfPHP\Component\HttpClient\Test\MockHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\HandlerStack;
use SonsOfPHP\Component\HttpClient\Middleware\HttpErrorMiddleware;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpClient\Test\MockHandler
 *
 * @internal
 */
final class MockHandlerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, new MockHandler());
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpectedWhenNoResponsesHaveBeenSet(): void
    {
        $handler = new MockHandler();
        $this->expectException('RuntimeException');
        $handler->handle($this->createMock(RequestInterface::class));
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpected(): void
    {
        $one = $this->createMock(ResponseInterface::class);
        $two = $this->createMock(ResponseInterface::class);

        $handler = new MockHandler([$one, $two]);
        $this->assertSame($one, $handler->handle($this->createMock(RequestInterface::class)));
        $this->assertSame($two, $handler->handle($this->createMock(RequestInterface::class)));

        $this->expectException('RuntimeException');
        $handler->handle($this->createMock(RequestInterface::class));
    }
}
