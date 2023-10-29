<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpMessage\Request;
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
     * @covers ::getMethod
     * @covers ::withMethod
     */
    public function testWithMethodWorksAsExpected(): void
    {
        $request = new Request();
        $req = $request->withMethod('BREW');

        $this->assertNotSame($request, $req);
        $this->assertSame('BREW', $req->getMethod());
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
}
