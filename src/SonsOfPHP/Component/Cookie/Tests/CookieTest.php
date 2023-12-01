<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\Cookie\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\Cookie\Cookie;
use SonsOfPHP\Contract\Cookie\CookieInterface;

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
        $cookie = new Cookie('test');

        $this->assertSame($cookie, $cookie->withSecure(false));
        $this->assertNotSame($cookie, $cookie->withSecure(true));
    }

    /**
     * @covers ::withHttpOnly
     */
    public function testWithHttpOnly(): void
    {
        $cookie = new Cookie('test');

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
}
