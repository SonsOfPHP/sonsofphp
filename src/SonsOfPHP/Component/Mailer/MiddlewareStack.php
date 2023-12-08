<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Mailer;

use SonsOfPHP\Contract\Mailer\MiddlewareInterface;
use SonsOfPHP\Contract\Mailer\MiddlewareStackInterface;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
class MiddlewareStack implements MiddlewareStackInterface, \Countable
{
    private array $middlewares = [];

    public function add(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function next(): MiddlewareInterface
    {
        return array_shift($this->middlewares);
    }

    public function count(): int
    {
        return count($this->middlewares);
    }
}
