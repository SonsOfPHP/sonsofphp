<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpMessage\Request
 *
 * @internal
 */
final class RequestTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(RequestInterface::class, new Request());
    }

    /**
     * @covers ::getRequestTarget
     * @covers ::withRequestTarget
     */
    public function testWithRequestTargetWorksAsExpected(): void
    {
        $request = new Request();
        $req = $request->withRequestTarget('/testing');

        $this->assertNotSame($request, $req);
        $this->assertSame('/testing', $req->getRequestTarget());
    }

    /**
     * @covers ::withRequestTarget
     */
    public function testWithRequestTargetWorksAsExpectedWhenSameRequestTarget(): void
    {
        $request = (new Request())->withRequestTarget('/testing');
        $req = $request->withRequestTarget('/testing');

        $this->assertSame($request, $req);
    }

    /**
     * @covers ::getMethod
     * @covers ::withMethod
     */
    public function testWithMethodWorksAsExpected(): void
    {
        $request = new Request();
        $req = $request->withMethod('get');

        $this->assertNotSame($request, $req);
        $this->assertSame('get', $req->getMethod());
    }

    /**
     * @covers ::withMethod
     */
    public function testWithMethodWorksAsExpectedWhenInvalidMethod(): void
    {
        $request = new Request();
        $this->expectException('InvalidArgumentException');
        $request->withMethod('not a valid method');
    }

    /**
     * @covers ::withMethod
     */
    public function testWithMethodWorksAsExpectedWhenMethodIsSame(): void
    {
        $request = new Request('get');
        $req = $request->withMethod('get');
        $this->assertSame($request, $req);
    }

    /**
     * @covers ::getUri
     * @covers ::withUri
     */
    public function testWithUriWorksAsExpected(): void
    {
        $request = new Request();
        $uri = $this->createMock(UriInterface::class);
        $req = $request->withUri($uri);

        $this->assertNotSame($request, $req);
        $this->assertSame($uri, $req->getUri());
    }

    /**
     * @covers ::withUri
     */
    public function testWithUriWorksAsExpectedWhenSame(): void
    {
        $request = new Request('get', 'https://docs.sonsofphp.com');
        $req = $request->withUri(new Uri('https://docs.sonsofphp.com'));

        $this->assertSame($request, $req);
    }

    /**
     * @covers ::__construct
     */
    public function testItWillThrowExceptionWhenInvalidMethod(): void
    {
        $this->expectException('InvalidArgumentException');
        $request = new Request('not valid');
    }

    /**
     * @covers ::__construct
     */
    public function testItCanBeCreatedWithStringAsUri(): void
    {
        $request = new Request('get', 'https://docs.sonsofphp.com');
        $this->assertSame('https://docs.sonsofphp.com', (string) $request->getUri());
    }
}
