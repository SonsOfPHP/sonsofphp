<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\ServerRequest;

#[CoversClass(ServerRequest::class)]
#[UsesClass(Request::class)]
final class ServerRequestTest extends TestCase
{
    #[CoversNothing]
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ServerRequestInterface::class, new ServerRequest());
    }

    public function testGetAttributesWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getAttributes());
    }

    public function testGetAttributeWorksAsExpected(): void
    {
        $req = (new ServerRequest())->withAttribute('controller', __METHOD__);
        $this->assertSame(__METHOD__, $req->getAttribute('controller'));
    }

    public function testGetAttributeWorksAsExpectedWithUnknownAttribute(): void
    {
        $req = new ServerRequest();
        $this->assertNull($req->getAttribute('controller'));
        $this->assertSame(__METHOD__, $req->getAttribute('controller', __METHOD__));
    }

    public function testWithAttributeWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withAttribute('controller', __METHOD__);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getAttributes());
        $this->assertCount(1, $req->getAttributes());
    }

    public function testWithoutAttributeWorksAsExpected(): void
    {
        $request = (new ServerRequest())->withAttribute('controller', __METHOD__);
        $this->assertCount(1, $request->getAttributes());

        $req = $request->withoutAttribute('controller');
        $this->assertNotSame($request, $req);
        $this->assertCount(1, $request->getAttributes());
        $this->assertCount(0, $req->getAttributes());
    }

    public function testGetServerParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();

        $this->assertGreaterThan(0, $req->getServerParams());
    }

    public function testGetCookieParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getCookieParams());
    }

    public function testWithCookieParamsWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withCookieParams(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getCookieParams());
        $this->assertCount(1, $req->getCookieParams());
    }

    public function testGetQueryParamsWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getQueryParams());
    }

    public function testWithQueryParamsWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withQueryParams(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getQueryParams());
        $this->assertCount(1, $req->getQueryParams());
    }

    public function testGetUploadedFilesWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertCount(0, $req->getUploadedFiles());
    }

    public function testWithUploadedFilesWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withUploadedFiles(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertCount(0, $request->getUploadedFiles());
        $this->assertCount(1, $req->getUploadedFiles());
    }

    public function testGetParsedBodyWorksAsExpected(): void
    {
        $req = new ServerRequest();
        $this->assertNull($req->getParsedBody());
    }

    public function testWithParsedBodyWorksAsExpected(): void
    {
        $request = new ServerRequest();
        $req = $request->withParsedBody(['name' => 'test']);

        $this->assertNotSame($request, $req);
        $this->assertNull($request->getParsedBody());
        $this->assertNotNull($req->getParsedBody());
    }
}
