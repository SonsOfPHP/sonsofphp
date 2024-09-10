<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\MiddlewareHandler;
use SonsOfPHP\Component\Mailer\MiddlewareStack;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;

#[CoversClass(MiddlewareHandler::class)]
#[UsesClass(MiddlewareStack::class)]
final class MiddlewareHandlerTest extends TestCase
{
    private MockObject $message;

    private MiddlewareStackInterface $stack;

    protected function setUp(): void
    {
        $this->message = $this->createMock(MessageInterface::class);
        $this->stack = new MiddlewareStack();
    }

    public function testItHasTheCorrectInterface(): void
    {
        $handler = new MiddlewareHandler();

        $this->assertInstanceOf(MiddlewareHandlerInterface::class, $handler);
    }

    public function testGetMiddlewareStack(): void
    {
        $handler = new MiddlewareHandler();
        $this->assertInstanceOf(MiddlewareStackInterface::class, $handler->getMiddlewareStack());

        $handler = new MiddlewareHandler($this->stack);
        $this->assertSame($this->stack, $handler->getMiddlewareStack());
    }

    public function testHandleWhenNoMoreMiddleware(): void
    {
        $handler = new MiddlewareHandler();

        $this->assertSame($this->message, $handler->handle($this->message));
    }

    public function testHandle(): void
    {
        $middleware = new class implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler): MessageInterface
            {
                return $message;
            }
        };

        $handler = new MiddlewareHandler($this->stack);
        $handler->getMiddlewareStack()->add($middleware);

        $this->assertSame($this->message, $handler->handle($this->message));
    }
}
