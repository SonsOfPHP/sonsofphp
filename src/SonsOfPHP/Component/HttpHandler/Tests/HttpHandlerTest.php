<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpHandler\HttpHandler;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpMessage\Response;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpHandler\HttpHandler
 *
 * @uses \SonsOfPHP\Component\HttpHandler\HttpHandler
 */
final class HttpHandlerTest extends TestCase
{
    private $request;
    private $response;
    private array $middlewares = [];

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);

        $this->middlewares[] = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return new Response();
            }
        };
    }

    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $handler = new HttpHandler();

        $this->assertInstanceOf(RequestHandlerInterface::class, $handler);
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $handler = new HttpHandler($this->middlewares);

        $this->assertNotNull($handler->handle($this->request));
    }
}
