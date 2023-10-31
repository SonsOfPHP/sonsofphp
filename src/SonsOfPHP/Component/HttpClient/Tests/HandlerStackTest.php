<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\HandlerStack;
use SonsOfPHP\Component\HttpClient\Middleware\HttpErrorMiddleware;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpClient\HandlerStack
 *
 * @internal
 */
final class HandlerStackTest extends TestCase
{
    private $req;
    private $res;

    public function setUp(): void
    {
        $this->req = $this->createMock(RequestInterface::class);
        $this->res = $this->createMock(ResponseInterface::class);
    }

    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(HandlerInterface::class, new HandlerStack());
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpectedWhenNoMiddleware(): void
    {
        $handler = new HandlerStack();
        $this->expectException('RuntimeException');
        $handler->handle($this->req);
    }

    /**
     * covers ::handle
     */
    //public function testHandleWorksAsExpected(): void
    //{
    //    $handler = new HandlerStack();
    //    $handler->push(new HttpErrorMiddleware());
    //    $handler->handle($this->req);
    //}
}
