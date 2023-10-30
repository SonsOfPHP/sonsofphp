<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\ResponseFactory
 *
 * @internal
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
     */
    public function testCreateResponseWorksAsExpected(): void
    {
        $factory = new ResponseFactory();

        $this->assertInstanceOf(ResponseInterface::class, $factory->createResponse());
    }
}
