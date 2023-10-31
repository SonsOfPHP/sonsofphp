<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpClient\Tests;

use SonsOfPHP\Component\HttpClient\Test\NullHandler;
use SonsOfPHP\Component\HttpClient\Test\NullMiddleware;
use SonsOfPHP\Component\HttpClient\Middleware\CallbackMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpClient\HandlerInterface;
use SonsOfPHP\Component\HttpClient\MiddlewareInterface;
use SonsOfPHP\Component\HttpClient\HandlerStack;
use SonsOfPHP\Component\HttpClient\Middleware\HttpErrorMiddleware;
use SonsOfPHP\Component\HttpClient\Middleware\ContentLengthMiddleware;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\Stream;

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
        $this->assertInstanceOf(HandlerInterface::class, new HandlerStack(new NullHandler()));
    }

    /**
     * @covers ::handle
     */
    public function testHandleWorksAsExpected(): void
    {
        $handler = new HandlerStack(new NullHandler());
        $handler->push(new HttpErrorMiddleware());
        $handler->push(new ContentLengthMiddleware());
        $handler->push(new CallbackMiddleware(function ($handler, $req, $res) { return $handler->handle($req, $res); }));
        $handler->push(new NullMiddleware());
        $response = $handler->handle((new Request())->withBody(new Stream()));

        $this->assertCount(0, (new \ReflectionProperty($handler, 'requestStack'))->getValue($handler));
        $this->assertCount(0, (new \ReflectionProperty($handler, 'responseStack'))->getValue($handler));
    }

    /**
     * @covers ::push
     */
    public function testPushWorksAsExpected(): void
    {
        $handler = new HandlerStack(new NullHandler());
        $handler->push($this->createMock(MiddlewareInterface::class));

        $this->assertCount(1, (new \ReflectionProperty($handler, 'requestStack'))->getValue($handler));
        $this->assertCount(1, (new \ReflectionProperty($handler, 'responseStack'))->getValue($handler));
    }

    /**
     * @covers ::push
     */
    public function testPushWorksAsExpectedWhenNameIsPassedIn(): void
    {
        $handler = new HandlerStack(new NullHandler());
        $handler->push($this->createMock(MiddlewareInterface::class), 'unit.test');

        $this->assertArrayHasKey('unit.test', (new \ReflectionProperty($handler, 'requestStack'))->getValue($handler));
        $this->assertArrayHasKey('unit.test', (new \ReflectionProperty($handler, 'responseStack'))->getValue($handler));
    }
}
