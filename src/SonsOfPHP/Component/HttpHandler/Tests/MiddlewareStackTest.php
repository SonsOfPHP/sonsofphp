<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpHandler\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpHandler\HttpHandler;
use SonsOfPHP\Component\HttpHandler\MiddlewareStack;
use SonsOfPHP\Contract\HttpHandler\MiddlewareStackInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpMessage\Response;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpHandler\MiddlewareStack
 *
 * @uses \SonsOfPHP\Component\HttpHandler\MiddlewareStack
 */
final class MiddlewareStackTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItHasTheCorrectInterface(): void
    {
        $stack = new MiddlewareStack();

        $this->assertInstanceOf(MiddlewareStackInterface::class, $stack);
    }

    /**
     * @covers ::add
     */
    public function testAdd(): void
    {
        $stack = new MiddlewareStack();
        $middlewares = new \ReflectionProperty($stack, 'middlewares');
        $this->assertCount(0, $middlewares->getValue($stack));

        $stack->add(function () {});
        $this->assertCount(1, $middlewares->getValue($stack));
    }

    /**
     * @covers ::count
     */
    public function testCount(): void
    {
        $stack = new MiddlewareStack();
        $this->assertCount(0, $stack);

        $stack->add(function () {});
        $this->assertCount(1, $stack);
    }

    /**
     * @covers ::add
     */
    public function testAddWillPrioritizeCorrectly(): void
    {
        $stack = new MiddlewareStack();
        $middlewares = new \ReflectionProperty($stack, 'middlewares');
        $this->assertCount(0, $middlewares->getValue($stack));

        $one   = function () {};
        $two   = function () {};
        $three = function () {};

        $stack->add($three, 255);
        $stack->add($two); // default
        $stack->add($one, -255);

        $middlewareStack = $middlewares->getValue($stack);
        $this->assertCount(3, $middlewareStack);
        $this->assertSame($one, $middlewareStack[-255][0]);
        $this->assertSame($two, $middlewareStack[0][0]);
        $this->assertSame($three, $middlewareStack[255][0]);
    }

    /**
     * @covers ::next
     */
    public function testNextReturnsMiddlewareIfClosure(): void
    {
        $stack = new MiddlewareStack();

        $stack->add(function () {});

        $this->assertInstanceOf(MiddlewareInterface::class, $stack->next());
    }

    /**
     * @covers ::next
     */
    public function testNextReturnsCorrectlyWhenMultipleMiddlewareHasSamePriority(): void
    {
        $stack = new MiddlewareStack();

        $one = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };
        $two = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };
        $three = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };


        $stack->add($one);
        $stack->add($two);
        $stack->add($three);

        $this->assertSame($one, $stack->next());
        $this->assertSame($two, $stack->next());
        $this->assertSame($three, $stack->next());
    }

    /**
     * @covers ::next
     */
    public function testNextReturnsMiddlewareInCorrectOrder(): void
    {
        $stack = new MiddlewareStack();

        $one = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };
        $two = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };
        $three = new class implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return $handler->handle($request);
            }
        };


        $stack->add($three, 255);
        $stack->add($two);
        $stack->add($one, -255);

        $this->assertSame($one, $stack->next());
        $this->assertSame($two, $stack->next());
        $this->assertSame($three, $stack->next());
    }
}
