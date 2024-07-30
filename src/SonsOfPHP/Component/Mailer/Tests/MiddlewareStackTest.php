<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use SonsOfPHP\Component\Mailer\MiddlewareStack;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;

/**
 * @uses \SonsOfPHP\Component\Mailer\MiddlewareStack
 * @coversNothing
 */
#[CoversClass(MiddlewareStack::class)]
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

    public function testAdd(): void
    {
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler): MessageInterface
            {
                return $message;
            }
        };
        $property = new ReflectionProperty(MiddlewareStack::class, 'middlewares');

        $stack = new MiddlewareStack();

        $this->assertCount(0, $property->getValue($stack));
        $stack->add($middleware);
        $this->assertCount(1, $property->getValue($stack));
    }

    public function testNext(): void
    {
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler): MessageInterface
            {
                return $message;
            }
        };
        $stack = new MiddlewareStack();
        $stack->add($middleware);

        $this->assertSame($middleware, $stack->next());
    }

    public function testCount(): void
    {
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler): MessageInterface
            {
                return $message;
            }
        };
        $stack = new MiddlewareStack();
        $stack->add($middleware);

        $this->assertCount(1, $stack);
    }
}
