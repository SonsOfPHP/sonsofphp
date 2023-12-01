<?php

declare(strict_types=1);

namespace SonsOfPHP\Contract\Cookie;

/**
 * @author Joshua Estes <joshua@sonsofphp.com>
 */
interface CookieInterface
{
    /**
     * Set's the cookie name
     *
     * If the $name is the same, it will return the same object, however if the
     * $name is different than the current $name, it will return a new instance
     * of cookie
     */
    public function withName(string $name): static;

    /**
     */
    public function withValue(string $value): static;

    /**
     */
    public function withPath(string $path): static;

    /**
     */
    public function withDomain(string $domain): static;

    /**
     */
    public function withSecure(bool $secure): static;

    /**
     */
    public function withHttpOnly(bool $httpOnly): static;

    /**
     * @throws CookieExceptionInterface if argument is invalid
     */
    public function withSameSite(string $sameSite): static;

    /**
     * @throws CookieExceptionInterface
     */
    public function withExpires(\DateTimeImmutable|int|string $expires): static;

    /**
     * @throws CookieExceptionInterface is something went wrong
     */
    public function send(bool $raw = false): void;
}
