<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cookie;

use DateTimeImmutable;
use Stringable;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CookieInterface extends Stringable
{
    /**
     * Returns the Header Value for "Set-Cookie"
     *
     * __toString and this method MUST return the same value
     */
    public function getHeaderValue(): string;

    /**
     * Set the cookie name
     *
     * If the $name is the same, it will return the same object, however if the
     * $name is different than the current $name, it will return a new instance
     * of cookie
     *
     * @throws CookieExceptionInterface if $name is invalid
     */
    public function withName(string $name): static;

    /**
     * Set the cookie value
     *
     * @throws CookieExceptionInterface if $value is invalid
     */
    public function withValue(string $value): static;

    /**
     * Set the "Path="
     *
     * If path has the same value as the existing path, this will not return a
     * new object
     */
    public function withPath(string $path): static;

    /**
     * Set the "Domain="
     *
     * If domain has the same value as the existing domain, this will not return a
     * new object
     */
    public function withDomain(string $domain): static;

    /**
     * Set the "SameSize="
     *
     * If sameSite has the same value as the existing sameSite, this will not return a
     * new object
     *
     * Only valid arguments allowed:
     *   - Strict
     *   - Lax
     *   - None
     *
     * @throws CookieExceptionInterface if argument is invalid
     */
    public function withSameSite(string $sameSite): static;

    /**
     * Set "Expires="
     *
     * If expires has the same value as the existing expires, this will not return a
     * new object
     *
     * @throws CookieExceptionInterface when $expires is invalid
     */
    public function withExpires(DateTimeImmutable $expires): static;

    /**
     * Set "Max-Age="
     *
     * This is the number of seconds before the cookie will expire. For
     * example, if "69" is passed in, it will expire in one minute and
     * 9 seconds.
     *
     * @throws CookieExceptionInterface when $maxAge is invalid
     */
    public function withMaxAge(int $maxAge): static;

    /**
     * Set "Secure"
     */
    public function withSecure(bool $secure): static;

    /**
     * Set "HttpOnly"
     */
    public function withHttpOnly(bool $httpOnly): static;

    /**
     * Set "Partitioned"
     */
    public function withPartitioned(bool $partitioned): static;
}
