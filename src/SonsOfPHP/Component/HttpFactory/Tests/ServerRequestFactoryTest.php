<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use SonsOfPHP\Component\HttpFactory\ServerRequestFactory;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\ServerRequestFactory
 *
 * @internal
 */
final class ServerRequestFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ServerRequestFactoryInterface::class, new ServerRequestFactory());
    }

    /**
     * @covers ::createServerRequest
     * @uses \SonsOfPHP\Component\HttpMessage\Request
     * @uses \SonsOfPHP\Component\HttpMessage\ServerRequest
     * @uses \SonsOfPHP\Component\HttpMessage\Uri
     */
    public function testCreateServerRequestWorksAsExpected(): void
    {
        $factory = new ServerRequestFactory();

        $this->assertInstanceOf(ServerRequestInterface::class, $factory->createServerRequest('get', 'https://docs.sonsofphp.com'));
    }
}
