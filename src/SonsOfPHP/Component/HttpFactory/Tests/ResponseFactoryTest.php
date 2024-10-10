<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use SonsOfPHP\Component\HttpFactory\ResponseFactory;
use SonsOfPHP\Component\HttpMessage\Response;

#[CoversClass(ResponseFactory::class)]
#[UsesClass(Response::class)]
final class ResponseFactoryTest extends TestCase
{
    #[CoversNothing]
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(ResponseFactoryInterface::class, new ResponseFactory());
    }


    #[DataProvider('validCreateResponseProvider')]
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
