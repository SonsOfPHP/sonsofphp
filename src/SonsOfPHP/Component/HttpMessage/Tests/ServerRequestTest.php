<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SonsOfPHP\Component\HttpMessage\ServerRequest;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpMessage\ServerRequest
 *
 * @uses \SonsOfPHP\Component\HttpMessage\Request
 * @uses \SonsOfPHP\Component\HttpMessage\ServerRequest
 */
final class ServerRequestTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ServerRequestInterface::class, new ServerRequest());
    }

    /**
     * @covers ::getAttributes
     */
    public function testGetAttributesWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getAttributes());
    }

    /**
     * @covers ::getAttribute
     */
    public function testGetAttributeWorksAsExpected(): void
    {
        $req = (new ServerRequest())->withAttribute('controller', __METHOD__);
        $this->assertSame(__METHOD__, $req->getAttribute('controller'));
    }

    /**
     * @covers ::getAttribute
     */
    public function testGetAttributeWorksAsExpectedWithUnknownAttribute(): void
    {
        $req = new ServerRequest();
        $this->assertNull($req->getAttribute('controller'));
        $this->assertSame(__METHOD__, $req->getAttribute('controller', __METHOD__));
    }

    /**
     * @covers ::withAttribute
     */
    public function testWithAttributeWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withAttribute('controller', __METHOD__);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getAttributes());
        $this->assertCount(1, $req->getAttributes());
    }

    /**
     * @covers ::withoutAttribute
     */
    public function testWithoutAttributeWorksAsExpected(): void
    {
        $request = (new ServerRequest())->withAttribute('controller', __METHOD__);
        $this->assertCount(1, $request->getAttributes());

        $req = $request->withoutAttribute('controller');
        $this->assertNotSame($request, $req);
        $this->assertCount(1, $request->getAttributes());
        $this->assertCount(0, $req->getAttributes());
    }

    /**
     * @covers ::getServerParams
     */
    public function testGetServerParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();

        $this->assertGreaterThan(0, $req->getServerParams());
    }

    /**
     * @covers ::getCookieParams
     */
    public function testGetCookieParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getCookieParams());
    }

    /**
     * @covers ::withCookieParams
     */
    public function testWithCookieParamsWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withCookieParams(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getCookieParams());
        $this->assertCount(1, $req->getCookieParams());
    }

    /**
     * @covers ::getQueryParams
     */
    public function testGetQueryParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getQueryParams());
    }

    /**
     * @covers ::withQueryParams
     */
    public function testWithQueryParamsWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withQueryParams(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getQueryParams());
        $this->assertCount(1, $req->getQueryParams());
    }

    /**
     * @covers ::getUploadedFiles
     */
    public function testGetUploadedFilesWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getUploadedFiles());
    }

    /**
     * @covers ::withUploadedFiles
     */
    public function testWithUploadedFilesWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withUploadedFiles(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getUploadedFiles());
        $this->assertCount(1, $req->getUploadedFiles());
    }

    /**
     * @covers ::getParsedBody
     */
    public function testGetParsedBodyWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertNull($req->getParsedBody());
    }

    /**
     * @covers ::withParsedBody
     */
    public function testWithParsedBodyWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withParsedBody(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertNull($request->getParsedBody());
        $this->assertNotNull($req->getParsedBody());
    }
}
