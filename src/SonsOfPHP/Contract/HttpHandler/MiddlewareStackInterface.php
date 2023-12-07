<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\HttpHandler;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareStackInterface extends \Countable
{
    public function next(): MiddlewareInterface;
}
