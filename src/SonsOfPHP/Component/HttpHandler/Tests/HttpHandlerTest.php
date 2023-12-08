<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SonsOfPHP\Component\HttpHandler\HttpHandler;
use SonsOfPHP\Component\HttpHandler\MiddlewareStack;
use SonsOfPHP\Component\HttpMessage\Response;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpHandler\HttpHandler
 *
 * @uses \SonsOfPHP\Component\HttpHandler\HttpHandler
 * @uses \SonsOfPHP\Component\HttpHandler\MiddlewareStack
 */
final class HttpHandlerTest extends TestCase
{
    private $request;
    private $response;
    private MiddlewareStack $stack;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stack = new MiddlewareStack();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $handler = new HttpHandler($this->stack);

        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $this->stack->add(new class () implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return new Response();
            }
        });
        $handler = new HttpHandler($this->stack);

        $this->assertNotNull($handler->handle($this->request));
    }
}
