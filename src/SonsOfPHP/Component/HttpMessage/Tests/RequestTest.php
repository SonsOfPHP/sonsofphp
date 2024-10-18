<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\Uri;

#[CoversClass(Request::class)]
#[UsesClass(Uri::class)]
final class RequestTest extends TestCase
{
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(RequestInterface::class, new Request());
    }

    public function testWithRequestTargetWorksAsExpected(): void
    {
        $request = new Request();
        $req = $request->withRequestTarget('/testing');

        $this->assertNotSame($request, $req);
        $this->assertSame('/testing', $req->getRequestTarget());
    }

    public function testWithRequestTargetWorksAsExpectedWhenSameRequestTarget(): void
    {
        $request = (new Request())->withRequestTarget('/testing');
        $req = $request->withRequestTarget('/testing');

        $this->assertSame($request, $req);
    }

    public function testWithMethodWorksAsExpected(): void
    {
        $request = new Request();
        $req = $request->withMethod('get');

        $this->assertNotSame($request, $req);
        $this->assertSame('get', $req->getMethod());
    }

    public function testWithMethodWorksAsExpectedWhenInvalidMethod(): void
    {
        $request = new Request();
        $this->expectException('InvalidArgumentException');
        $request->withMethod('not a valid method');
    }

    public function testWithMethodWorksAsExpectedWhenMethodIsSame(): void
    {
        $request = new Request('get');
        $req = $request->withMethod('get');
        $this->assertSame($request, $req);
    }

    public function testWithUriWorksAsExpected(): void
    {
        $request = new Request();
        $uri = $this->createMock(UriInterface::class);
        $req = $request->withUri($uri);

        $this->assertNotSame($request, $req);
        $this->assertSame($uri, $req->getUri());
    }

    public function testWithUriWorksAsExpectedWhenSame(): void
    {
        $request = new Request('get', 'https://docs.sonsofphp.com');
        $req = $request->withUri(new Uri('https://docs.sonsofphp.com'));

        $this->assertSame($request, $req);
    }

    public function testItWillThrowExceptionWhenInvalidMethod(): void
    {
        $this->expectException('InvalidArgumentException');
        new Request('not valid');
    }

    public function testItCanBeCreatedWithStringAsUri(): void
    {
        $request = new Request('get', 'https://docs.sonsofphp.com');
        $this->assertSame('https://docs.sonsofphp.com', (string) $request->getUri());
    }
}
