<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cookie\Cookie;
use SonsOfPHP\Contract\Cookie\CookieExceptionInterface;
use SonsOfPHP\Contract\Cookie\CookieInterface;

/**
 * @uses \SonsOfPHP\Component\Cookie\Cookie
 * @coversNothing
 */
#[CoversClass(Cookie::class)]
final class CookieTest extends TestCase
{
    public function testItHasTheCorrectInterface(): void
    {
        $cookie = new Cookie('test');

        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }

    public function testWithName(): void
    {
        $cookie = new Cookie('test');

        $this->assertSame($cookie, $cookie->withName('test'));
        $this->assertNotSame($cookie, $cookie->withName('test2'));
    }

    public function testWithValue(): void
    {
        $cookie = new Cookie('test', 'value');

        $this->assertSame($cookie, $cookie->withValue('value'));
        $this->assertNotSame($cookie, $cookie->withValue('value2'));
    }

    public function testWithPath(): void
    {
        $cookie = (new Cookie('test'))->withPath('/');

        $this->assertSame($cookie, $cookie->withPath('/'));
        $this->assertNotSame($cookie, $cookie->withPath('/testing'));
    }

    public function testWithDomain(): void
    {
        $cookie = (new Cookie('test'))->withDomain('sonsofphp.com');

        $this->assertSame($cookie, $cookie->withDomain('sonsofphp.com'));
        $this->assertNotSame($cookie, $cookie->withDomain('docs.sonsofphp.com'));
    }

    public function testWithSecure(): void
    {
        $cookie = (new Cookie('test'))->withSecure(false);

        $this->assertSame($cookie, $cookie->withSecure(false));
        $this->assertNotSame($cookie, $cookie->withSecure(true));
    }

    public function testWithHttpOnly(): void
    {
        $cookie = (new Cookie('test'))->withHttpOnly(false);

        $this->assertSame($cookie, $cookie->withHttpOnly(false));
        $this->assertNotSame($cookie, $cookie->withHttpOnly(true));
    }

    public function testWithSameSite(): void
    {
        $cookie = (new Cookie('test'))->withSameSite('none');

        $this->assertSame($cookie, $cookie->withSameSite('none'));
        $this->assertNotSame($cookie, $cookie->withSameSite('strict'));
    }

    public function testWithSameSiteWithThrowExceptionOnInvalidArgument(): void
    {
        $cookie = new Cookie('test');

        $this->expectException(CookieExceptionInterface::class);
        $cookie->withSameSite('not valid');
    }

    public function testWithPartitioned(): void
    {
        $cookie = (new Cookie('test'))->withPartitioned(false);

        $this->assertSame($cookie, $cookie->withPartitioned(false));
        $this->assertNotSame($cookie, $cookie->withPartitioned(true));
    }

    public function testHeaderValue(): void
    {
        $cookie = (new Cookie('name', 'value'))->withPath('/')->withPartitioned(false)->withHttpOnly(true);

        $this->assertSame('name=value; Path=/; HttpOnly', $cookie->getHeaderValue());
    }

    public function testToString(): void
    {
        $cookie = (new Cookie('name', 'value'))->withPath('/')->withPartitioned(false)->withHttpOnly(true);

        $this->assertSame($cookie->getHeaderValue(), (string) $cookie);
    }

    public function testMaxAge(): void
    {
        $cookie = (new Cookie('name', 'value'))->withMaxAge(0);

        $this->assertSame($cookie, $cookie->withMaxAge(0));
        $this->assertNotSame($cookie, $cookie->withMaxAge(420));

        $this->assertStringContainsString('Max-Age=', $cookie->getHeaderValue());
    }

    public function testExpires(): void
    {
        $timestamp = new DateTimeImmutable('2020-04-20 04:20:00');
        $cookie = (new Cookie('name', 'value'))->withExpires($timestamp);

        $this->assertSame($cookie, $cookie->withExpires($timestamp));
        $this->assertNotSame($cookie, $cookie->withExpires(new DateTimeImmutable()));

        $this->assertStringContainsString('Expires=Mon, 20 Apr 2020 04:20:00 +0000', $cookie->getHeaderValue());
    }
}
