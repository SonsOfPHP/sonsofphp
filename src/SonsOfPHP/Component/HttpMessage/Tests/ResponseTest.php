<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpMessage\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpMessage\Response;

#[CoversClass(Response::class)]
final class ResponseTest extends TestCase
{
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ResponseInterface::class, new Response());
    }

    public function testWithStatusWorksAsExpected(): void
    {
        $response = new Response();
        $res = $response->withStatus(200, 'Kind of OK');

        $this->assertNotSame($response, $res);
        $this->assertSame(200, $res->getStatusCode());
        $this->assertSame('Kind of OK', $res->getReasonPhrase());
    }
}
