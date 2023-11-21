<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;

/**
 * @coversDefaultClass \SonsOfPHP\Component\HttpFactory\ResponseFactory
 * @uses \SonsOfPHP\Component\HttpMessage\Response
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
     * @dataProvider validCreateResponseProvider
     *
     * @covers ::createResponse
     */
    public function testCreateResponseWorksAsExpected(int $code, string $reasonPhrase): void
    {
        $factory = new ResponseFactory();

        $this->assertInstanceOf(ResponseInterface::class, $factory->createResponse($code, $reasonPhrase));
    }

    public static function validCreateResponseProvider(): iterable
    {
        yield [200, 'OK'];
        yield [201, 'Not Content'];
        yield [404, 'Not Found'];
    }
}
