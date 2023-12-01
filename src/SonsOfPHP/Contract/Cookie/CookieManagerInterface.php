<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cookie;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CookieManagerInterface
{
    public function get(string $name): CookieInterface;

    public function has(string $name): bool;

    public function remove(string $name): bool;
}
