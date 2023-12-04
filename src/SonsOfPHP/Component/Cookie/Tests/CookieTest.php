<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cookie\Cookie;
use SonsOfPHP\Contract\Cookie\CookieInterface;
use SonsOfPHP\Contract\Cookie\CookieExceptionInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\Cookie\Cookie
 *
 * @uses \SonsOfPHP\Component\Cookie\Cookie
 */
final class CookieTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testItHasTheCorrectInterface(): void
    {
        $cookie = new Cookie('test');

        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }

    /**
     * @covers ::withName
     */
    public function testWithName(): void
    {
        $cookie = new Cookie('test');

        $this->assertSame($cookie, $cookie->withName('test'));
        $this->assertNotSame($cookie, $cookie->withName('test2'));
    }

    /**
     * @covers ::withValue
     */
    public function testWithValue(): void
    {
        $cookie = new Cookie('test', 'value');

        $this->assertSame($cookie, $cookie->withValue('value'));
        $this->assertNotSame($cookie, $cookie->withValue('value2'));
    }

    /**
     * @covers ::withPath
     */
    public function testWithPath(): void
    {
        $cookie = (new Cookie('test'))->withPath('/');

        $this->assertSame($cookie, $cookie->withPath('/'));
        $this->assertNotSame($cookie, $cookie->withPath('/testing'));
    }

    /**
     * @covers ::withDomain
     */
    public function testWithDomain(): void
    {
        $cookie = (new Cookie('test'))->withDomain('sonsofphp.com');

        $this->assertSame($cookie, $cookie->withDomain('sonsofphp.com'));
        $this->assertNotSame($cookie, $cookie->withDomain('docs.sonsofphp.com'));
    }

    /**
     * @covers ::withSecure
     */
    public function testWithSecure(): void
    {
        $cookie = (new Cookie('test'))->withSecure(false);

        $this->assertSame($cookie, $cookie->withSecure(false));
        $this->assertNotSame($cookie, $cookie->withSecure(true));
    }

    /**
     * @covers ::withHttpOnly
     */
    public function testWithHttpOnly(): void
    {
        $cookie = (new Cookie('test'))->withHttpOnly(false);

        $this->assertSame($cookie, $cookie->withHttpOnly(false));
        $this->assertNotSame($cookie, $cookie->withHttpOnly(true));
    }

    /**
     * @covers ::withSameSite
     */
    public function testWithSameSite(): void
    {
        $cookie = (new Cookie('test'))->withSameSite('none');

        $this->assertSame($cookie, $cookie->withSameSite('none'));
        $this->assertNotSame($cookie, $cookie->withSameSite('strict'));
    }

    /**
     * @covers ::withSameSite
     */
    public function testWithSameSiteWithThrowExceptionOnInvalidArgument(): void
    {
        $cookie = new Cookie('test');

        $this->expectException(CookieExceptionInterface::class);
        $cookie->withSameSite('not valid');
    }

    /**
     * @covers ::withPartitioned
     */
    public function testWithPartitioned(): void
    {
        $cookie = (new Cookie('test'))->withPartitioned(false);

        $this->assertSame($cookie, $cookie->withPartitioned(false));
        $this->assertNotSame($cookie, $cookie->withPartitioned(true));
    }

    /**
     * @covers ::getHeaderValue
     */
    public function testHeaderValue(): void
    {
        $cookie = (new Cookie('name', 'value'))->withPath('/')->withPartitioned(false)->withHttpOnly(true);

        $this->assertSame('name=value; Path=/; HttpOnly', $cookie->getHeaderValue());
    }

    /**
     * @covers ::__toString
     */
    public function testToString(): void
    {
        $cookie = (new Cookie('name', 'value'))->withPath('/')->withPartitioned(false)->withHttpOnly(true);

        $this->assertSame($cookie->getHeaderValue(), (string) $cookie);
    }

    /**
     * @covers ::withMaxAge
     */
    public function testMaxAge(): void
    {
        $cookie = (new Cookie('name', 'value'))->withMaxAge(0);

        $this->assertSame($cookie, $cookie->withMaxAge(0));
        $this->assertNotSame($cookie, $cookie->withMaxAge(420));

        $this->assertStringContainsString('Max-Age=', $cookie->getHeaderValue());
    }

    /**
     * @covers ::withExpires
     */
    public function testExpires(): void
    {
        $timestamp = new \DateTimeImmutable('2020-04-20 04:20:00');
        $cookie = (new Cookie('name', 'value'))->withExpires($timestamp);

        $this->assertSame($cookie, $cookie->withExpires($timestamp));
        $this->assertNotSame($cookie, $cookie->withExpires(new \DateTimeImmutable()));

        $this->assertStringContainsString('Expires=Mon, 20 Apr 2020 04:20:00 +0000', $cookie->getHeaderValue());
    }
}
