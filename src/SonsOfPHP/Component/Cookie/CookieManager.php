<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie;

use SonsOfPHP\Contract\Cookie\CookieManagerInterface;
use SonsOfPHP\Contract\Cookie\CookieInterface;
use SonsOfPHP\Component\Cookie\Exception\CookieException;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
final class CookieManager implements CookieManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function get(string $name): CookieInterface
    {
        $cookie = new Cookie($name);

        if ($this->has($name)) {
            $cookie = $cookie->withValue($_COOKIE[$name]);
        }

        return $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $name): CookieInterface
    {
        return array_key_exists($name, $_COOKIE);
    }

    /**
     * {@inheritdoc}
     */
    //public function remove(string $name): CookieInterface
    //{
    //}
}
