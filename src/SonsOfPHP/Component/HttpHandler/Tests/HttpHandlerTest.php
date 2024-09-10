<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SonsOfPHP\Component\HttpHandler\HttpHandler;
use SonsOfPHP\Component\HttpHandler\MiddlewareStack;
use SonsOfPHP\Component\HttpMessage\Response;

#[CoversClass(HttpHandler::class)]
#[UsesClass(MiddlewareStack::class)]
final class HttpHandlerTest extends TestCase
{
    private MockObject $request;
    private MockObject $response;
    private MiddlewareStack $stack;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stack = new MiddlewareStack();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $handler = new HttpHandler($this->stack);

        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
    }

    public function testHandle(): void
    {
        $this->stack->add(new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return new Response();
            }
        });
        $handler = new HttpHandler($this->stack);

        $this->assertNotNull($handler->handle($this->request));
    }
}
