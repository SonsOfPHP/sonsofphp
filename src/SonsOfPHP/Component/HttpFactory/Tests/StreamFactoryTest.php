<?php

declare(strict_types=1);

namespace SonsOfPHP\Component\HttpFactory\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use SonsOfPHP\Component\HttpFactory\StreamFactory;
use SonsOfPHP\Component\HttpMessage\Stream;

#[CoversClass(StreamFactory::class)]
#[UsesClass(Stream::class)]
final class StreamFactoryTest extends TestCase
{
    #[CoversNothing]
    public function testItImplementsCorrectInterface(): void
    {
        $this->assertInstanceOf(StreamFactoryInterface::class, new StreamFactory());
    }

    public function testCreateStreamWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStream('just a test'));
    }

    public function testCreateStreamFromResourceWorksAsExpected(): void
    {
        $factory = new StreamFactory();

        $this->assertInstanceOf(StreamInterface::class, $factory->createStreamFromResource(fopen('php://memory', 'r')));
    }

    public function testCreateStreamFromResourceWillThrowExceptionWhenNotResource(): void
    {
        $factory = new StreamFactory();

        $this->expectException('InvalidArgumentException');
        $factory->createStreamFromResource('testing');
    }
}
