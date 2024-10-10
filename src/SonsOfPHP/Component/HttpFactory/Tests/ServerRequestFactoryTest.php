<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use SonsOfPHP\Component\HttpFactory\ServerRequestFactory;
use SonsOfPHP\Component\HttpMessage\Request;
use SonsOfPHP\Component\HttpMessage\ServerRequest;
use SonsOfPHP\Component\HttpMessage\Uri;

#[CoversClass(ServerRequestFactory::class)]
#[UsesClass(Request::class)]
#[UsesClass(ServerRequest::class)]
#[UsesClass(Uri::class)]
final class ServerRequestFactoryTest extends TestCase
{
    #[CoversNothing]
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, new ServerRequestFactory());
    }

    public function testCreateServerRequestWorksAsExpected(): void
    {
        $factory = new ServerRequestFactory();

        $this->assertInstanceOf(ServerRequestInterface::class, $factory->createServerRequest('get', 'https://docs.sonsofphp.com'));
    }
}
