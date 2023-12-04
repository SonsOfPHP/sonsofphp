<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cookie;

/**
 * In more advanced applications, you may need to get/set multiple cookies
 *
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CookieManagerInterface
{
    /**
     * If a cookie does not exists, this will create a new Cookie object and
     * return that.
     *
     * Example:
     *   $cookie = $manager->get('PHPSESSID');
     */
    public function get(string $name): CookieInterface;

    /**
     * Checks to see if "$name" exists in the request cookies
     *
     * Example:
     *   if ($manager->has('PHPSESSID')) {
     *     // ...
     *   }
     */
    public function has(string $name): bool;

    /**
     * Removes the cookie, this will remove from the browser as well
     *
     * If this return true, everything went ok, if it returns false, something
     * is broken. If thise throws an exception, something really fucked up
     * happened
     *
     * @throws CookieExceptionInterface
     */
    //public function remove(string $name): bool;
}
