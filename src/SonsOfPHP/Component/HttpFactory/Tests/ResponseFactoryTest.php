<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\ResponseFactory
 */
final class ResponseFactoryTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ResponseFactoryInterface::class, new ResponseFactory());
    }

    /**
     * @covers ::createResponse
     * @uses \SonsOfPHP\Component\HttpMessage\Response
     */
    public function testCreateResponseWorksAsExpected(): void
    {
        $factory = new ResponseFactory();

        $this->assertInstanceOf(ResponseInterface::class, $factory->createResponse());
    }
}
