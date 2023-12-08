<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Mailer\MiddlewareStack;
use SonsOfPHP\Contract\Mailer\MessageInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareHandlerInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Mailer\MiddlewareStack
 *
 * @uses \SonsOfPHP\Component\Mailer\MiddlewareStack
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
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler)
            {
                return $message;
            }
        };
        $property = new \ReflectionProperty(MiddlewareStack::class, 'middlewares');

        $stack = new MiddlewareStack();

        $this->assertCount(0, $property->getValue($stack));
        $stack->add($middleware);
        $this->assertCount(1, $property->getValue($stack));
    }

    /**
     * @covers ::next
     */
    public function testNext(): void
    {
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler)
            {
                return $message;
            }
        };
        $stack = new MiddlewareStack();
        $stack->add($middleware);

        $this->assertSame($middleware, $stack->next());
    }

    /**
     * @covers ::count
     */
    public function testCount(): void
    {
        $middleware = new class () implements MiddlewareInterface {
            public function __invoke(MessageInterface $message, MiddlewareHandlerInterface $handler)
            {
                return $message;
            }
        };
        $stack = new MiddlewareStack();
        $stack->add($middleware);

        $this->assertCount(1, $stack);
    }
}
