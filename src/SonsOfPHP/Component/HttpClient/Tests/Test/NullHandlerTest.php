<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests\Test;

use SonsOfPHP\Component\HttpClient\Test\NullHandler;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\HandlerStack;
use SonsOfPHP\Component\HttpClient\Middleware\HttpErrorMiddleware;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpClient\Test\NullHandler
 *
 * @internal
 */
final class NullHandlerTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, new NullHandler());
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpectedWhenResponseIsPassedIn(): void
    {
        $handler = new NullHandler();
        $response = $this->createMock(ResponseInterface::class);
        $this->assertSame($response, $handler->handle($this->createMock(RequestInterface::class), $response));
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpectedWhenNoResponseIsPassedIn(): void
    {
        $handler = new NullHandler();
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($this->createMock(RequestInterface::class)));
    }
}
