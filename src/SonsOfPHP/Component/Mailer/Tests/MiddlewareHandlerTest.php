<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\MiddlewareHandler;
use SonsOfPHP\Component\Mailer\MiddlewareStack;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;
use SonsOfPHP\Contract\Mailer\MessageInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Mailer\MiddlewareHandler
 *
 * @uses \SonsOfPHP\Component\Mailer\MiddlewareHandler
 * @uses \SonsOfPHP\Component\Mailer\MiddlewareStack
 */
final class MiddlewareHandlerTest extends TestCase
{
    private $message;
    private MiddlewareStackInterface $stack;

    public function setUp(): void
    {
        $this->message = $this->createMock(MessageInterface::class);
        $this->stack = new MiddlewareStack();
    }

    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $handler = new MiddlewareHandler();

        $this->assertInstanceOf(MiddlewareHandlerInterface::class, $handler);
    }

    /**
     * @covers ::getMiddlewareStack
     */
    public function testGetMiddlewareStack(): void
    {
        $handler = new MiddlewareHandler();
        $this->assertInstanceOf(MiddlewareStackInterface::class, $handler->getMiddlewareStack());

        $handler = new MiddlewareHandler($this->stack);
        $this->assertSame($this->stack, $handler->getMiddlewareStack());
    }

    /**
     * @covers ::handle
     */
    public function testHandleWhenNoMoreMiddleware(): void
    {
        $handler = new MiddlewareHandler();

        $this->assertSame($this->message, $handler->handle($this->message));
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler)
            {
                return $message;
            }
        };

        $handler = new MiddlewareHandler($this->stack);
        $handler->getMiddlewareStack()->add($middleware);

        $this->assertSame($this->message, $handler->handle($this->message));
    }
}
